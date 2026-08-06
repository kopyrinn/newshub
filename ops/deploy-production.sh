#!/usr/bin/env bash

set -Eeuo pipefail
umask 022

readonly APP_DIR="/www/wwwroot/newshub.kz"
readonly BACKUP_DIR="/root/newshub-deploy-backups"
readonly LOCK_FILE="/var/lock/newshub-deploy.lock"
readonly SUPERVISORCTL="/www/server/panel/pyenv/bin/supervisorctl"
readonly SUPERVISOR_CONFIG="/etc/supervisor/supervisord.conf"
readonly TARGET_SHA="${1:-}"
readonly SERVICES=(
    "horizon:horizon_00"
    "schedule:schedule_00"
    "octane:octane_00"
    "ssr:ssr_00"
)

export PATH="/www/server/nodejs/v20.18.0/bin:/www/server/php/82/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"
export COMPOSER_ALLOW_SUPERUSER=1
export GIT_TERMINAL_PROMPT=0

maintenance_enabled=0
services_stopped=0

log() {
    printf '[deploy] %s\n' "$*"
}

restore_runtime_on_error() {
    exit_code=$?

    if [[ $exit_code -eq 0 ]]; then
        return
    fi

    log "Deployment failed with exit code $exit_code."

    if [[ $services_stopped -eq 1 ]]; then
        "$SUPERVISORCTL" -c "$SUPERVISOR_CONFIG" start "${SERVICES[@]}" || true
    fi

    if [[ $maintenance_enabled -eq 1 && -f "$APP_DIR/artisan" ]]; then
        (
            cd "$APP_DIR"
            php artisan up
        ) || true
    fi
}

trap restore_runtime_on_error EXIT

if [[ ! "$TARGET_SHA" =~ ^[0-9a-f]{40}$ ]]; then
    echo "Usage: newshub-deploy <40-character-commit-sha>" >&2
    exit 64
fi

exec 9>"$LOCK_FILE"
if ! flock -n 9; then
    echo "Another production deployment is already running." >&2
    exit 75
fi

cd "$APP_DIR"

if [[ "$(git branch --show-current)" != "main" ]]; then
    echo "Production checkout is not on the main branch." >&2
    exit 65
fi

if ! git diff --quiet || ! git diff --cached --quiet; then
    echo "Production has tracked changes; refusing to overwrite them." >&2
    git status --short
    exit 65
fi

log "Fetching origin/main."
git fetch --prune origin main

remote_sha="$(git rev-parse origin/main)"
if [[ "$remote_sha" != "$TARGET_SHA" ]]; then
    echo "Requested commit is no longer the tip of origin/main; a newer workflow will deploy it." >&2
    exit 65
fi

if [[ -f package-lock.json ]] && ! git ls-files --error-unmatch package-lock.json >/dev/null 2>&1; then
    install -d -m 700 "$BACKUP_DIR"
    mv package-lock.json "$BACKUP_DIR/package-lock.before-tracking"
    log "Preserved the previously untracked package-lock.json."
fi

log "Enabling maintenance mode and stopping managed application processes."
php artisan down --retry=60
maintenance_enabled=1
"$SUPERVISORCTL" -c "$SUPERVISOR_CONFIG" stop "${SERVICES[@]}"
services_stopped=1

log "Fast-forwarding production to $TARGET_SHA."
git merge --ff-only "$TARGET_SHA"

if [[ "$(git rev-parse HEAD)" != "$TARGET_SHA" ]]; then
    echo "Production HEAD does not match the requested commit." >&2
    exit 65
fi

log "Installing PHP dependencies."
composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

log "Installing Node.js dependencies."
npm ci --legacy-peer-deps --no-audit --no-fund

log "Building client and SSR bundles."
npm run build
npm run ssr

log "Refreshing compiled Laravel caches."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache

log "Creating the isolated ad statistics table (does not touch other tables)."
php artisan migrate --path=database/migrations/2026_07_27_000000_create_ad_stats_table.php --force

log "Applying the verified banner statistics correction for July 23-26."
php artisan migrate --path=database/migrations/2026_07_31_130000_reapply_banner_stats_after_config_refresh.php --force

log "Removing the obsolete advertising microsite link."
php artisan migrate --path=database/migrations/2026_08_01_120000_remove_obsolete_advertising_link_from_ads_page.php --force

log "Verifying and force-removing any remaining obsolete advertising link markup."
php artisan migrate --path=database/migrations/2026_08_01_120100_force_remove_obsolete_advertising_link.php --force

log "Adding storage for the optional NewsHub editorial signature."
php artisan migrate --path=database/migrations/2026_08_05_180000_add_newshub_signature_to_posts_table.php --force

log "Starting managed application processes."
"$SUPERVISORCTL" -c "$SUPERVISOR_CONFIG" start "${SERVICES[@]}"
services_stopped=0

php artisan up
maintenance_enabled=0

log "Running health checks."
curl --fail --silent --show-error --max-time 15 --output /dev/null \
    http://127.0.0.1:3000/

octane_status="$(curl --silent --show-error --max-time 15 --output /dev/null \
    --write-out '%{http_code}' http://127.0.0.1:8002/)"
if [[ "$octane_status" != "404" ]]; then
    echo "Unexpected Octane root status: $octane_status" >&2
    exit 69
fi

curl --fail --silent --show-error --max-time 30 --output /dev/null \
    --retry 12 \
    --retry-all-errors \
    --retry-delay 5 \
    --retry-max-time 90 \
    https://newshub.kz/

"$SUPERVISORCTL" -c "$SUPERVISOR_CONFIG" status "${SERVICES[@]}"
composer check-platform-reqs --no-dev

log "Production successfully deployed at $TARGET_SHA."
