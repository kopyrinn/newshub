<template>
    <div v-if="$isWeb" class="app-downloads text-center">
        <div class="app-downloads__title fw-semibold text-muted">
            {{ $t('Download the app') }}
        </div>

        <div class="app-downloads__badges d-flex align-items-center justify-content-center flex-wrap">
            <a
                href="https://apps.apple.com/kz/app/newshub-kz/id1604898976"
                class="app-download-badge"
                target="_blank"
                rel="noopener noreferrer"
                :aria-label="$t('Download NewsHub on the App Store')"
            >
                <i class="bi bi-apple app-download-badge__icon app-download-badge__icon--apple" aria-hidden="true"></i>
                <span class="app-download-badge__copy">
                    <small>{{ $t('Download on the') }}</small>
                    <strong>App Store</strong>
                </span>
            </a>

            <a
                href="https://play.google.com/store/apps/details?id=kz.newshub.application"
                class="app-download-badge"
                target="_blank"
                rel="noopener noreferrer"
                :aria-label="$t('Get NewsHub on Google Play')"
            >
                <svg class="app-download-badge__icon app-download-badge__icon--play" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#00d7fe" d="M3 2.2 12 11 3 21.8Z"/>
                    <path fill="#00f076" d="M3 2.2 20.5 11.3 12 11Z"/>
                    <path fill="#ff3a44" d="M3 21.8 12 13 20.5 12.7Z"/>
                    <path fill="#ffce00" d="m12 11 8.5.3v1.4L12 13l-1-1Z"/>
                </svg>
                <span class="app-download-badge__copy">
                    <small>{{ $t('Available on') }}</small>
                    <strong>Google Play</strong>
                </span>
            </a>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    name: 'AppDownloadBadges',
})
</script>

<style scoped>
.app-downloads {
    margin: -2px 0 24px;
}

.app-downloads__title {
    margin-bottom: 8px;
    font-size: 12px;
}

.app-downloads__badges {
    gap: 8px;
}

.app-download-badge {
    display: inline-flex;
    align-items: center;
    width: 126px;
    height: 38px;
    padding: 4px 9px;
    color: #fff;
    text-align: left;
    text-decoration: none;
    background: #080808;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 7px;
    transition: transform .15s ease, opacity .15s ease;
}

.app-download-badge:hover {
    color: #fff;
    opacity: .9;
    transform: translateY(-1px);
}

.app-download-badge__icon {
    flex: 0 0 auto;
    margin-right: 7px;
    font-size: 22px;
    line-height: 1;
}

.app-download-badge__icon--apple {
    color: #fff !important;
}

.app-download-badge__icon--play {
    width: 20px;
    height: 20px;
    font-size: 20px;
}

.app-download-badge__copy {
    display: flex;
    min-width: 0;
    flex-direction: column;
    line-height: 1;
}

.app-download-badge__copy small {
    margin-bottom: 2px;
    overflow: hidden;
    font-size: 7px;
    line-height: 1;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.app-download-badge__copy strong {
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
    white-space: nowrap;
}

@media (max-width: 575.98px) {
    .app-downloads {
        margin-bottom: 20px;
    }

    .app-download-badge {
        width: 116px;
        height: 36px;
        padding-inline: 8px;
    }

    .app-download-badge__icon {
        margin-right: 6px;
        font-size: 20px;
    }

    .app-download-badge__icon--play {
        width: 18px;
        height: 18px;
        font-size: 18px;
    }

    .app-download-badge__copy strong {
        font-size: 12px;
    }
}
</style>
