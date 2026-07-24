#!/usr/bin/env bash

set -Eeuo pipefail

read -r -a command_parts <<< "${SSH_ORIGINAL_COMMAND:-}"

if [[ ${#command_parts[@]} -ne 2 || ${command_parts[0]} != "deploy" ]]; then
    echo "Only 'deploy <commit-sha>' is allowed." >&2
    exit 64
fi

target_sha="${command_parts[1]}"

if [[ ! "$target_sha" =~ ^[0-9a-f]{40}$ ]]; then
    echo "Invalid commit SHA." >&2
    exit 64
fi

exec sudo -n /usr/local/sbin/newshub-deploy "$target_sha"
