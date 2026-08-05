<template>
    <div class="nh-post-tools">
        <div class="nh-post-tools__top">
            <label class="nh-post-tools__switch">
                <input v-model="enabled" type="checkbox">
                <span class="nh-post-tools__switch-track" aria-hidden="true"></span>
                <span>
                    <strong>Добавить фирменную подпись NewsHub</strong>
                    <small>Telegram, Instagram, Android и iOS в конце публикации</small>
                </span>
            </label>

            <div class="nh-post-tools__actions">
                <button type="button" class="nh-post-tools__button" @click="editorOpen = !editorOpen">
                    {{ editorOpen ? 'Скрыть редактор' : 'Редактировать подпись' }}
                </button>
                <button type="button" class="nh-post-tools__button nh-post-tools__button--primary" @click="openPreview">
                    Предпросмотр публикации
                </button>
            </div>
        </div>

        <div v-if="editorOpen" class="nh-post-tools__editor">
            <label for="news-hub-signature-template">Текст подписи</label>
            <textarea id="news-hub-signature-template" v-model="template" rows="4" maxlength="1000"></textarea>
            <div class="nh-post-tools__hint">
                Оставьте маркеры ссылок: {telegram}, {instagram}, {android}, {ios}
            </div>
            <div class="nh-post-tools__editor-footer">
                <button type="button" class="nh-post-tools__reset" @click="resetTemplate">Сбросить подпись</button>
                <div class="nh-post-tools__signature-preview" v-html="signatureHtml"></div>
            </div>
        </div>

        <Teleport to="body">
            <div v-if="previewOpen" class="nh-post-preview" @click.self="previewOpen = false">
                <div class="nh-post-preview__dialog">
                    <header class="nh-post-preview__header">
                        <div>
                            <strong>Предпросмотр публикации</strong>
                            <small>Материал ещё не сохранён</small>
                        </div>
                        <button type="button" aria-label="Закрыть" @click="previewOpen = false">×</button>
                    </header>

                    <div class="nh-post-preview__scroll">
                        <article class="nh-post-preview__article">
                            <h1>{{ preview.title || 'Без заголовка' }}</h1>
                            <p v-if="preview.summary" class="nh-post-preview__summary">{{ preview.summary }}</p>
                            <img v-if="preview.image" :src="preview.image" :alt="preview.title || ''">
                            <div class="nh-post-preview__content" v-html="preview.content"></div>
                        </article>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
const links = {
    '{telegram}': ['https://t.me/NewsHub_Channel', 'Telegram'],
    '{instagram}': ['https://www.instagram.com/news_hub.kz/', 'Instagram'],
    '{android}': ['https://play.google.com/store/apps/details?id=kz.newshub.application', 'Android'],
    '{ios}': ['https://apps.apple.com/kz/app/newshub-kz/id1604898976', 'iOS'],
}

const marker = 'newshub-editorial-signature'

const escapeHtml = (value) => String(value || '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;')

const buildSignature = (template, fallback) => {
    let content = escapeHtml((template || '').trim() || fallback).replaceAll('\n', '<br>')

    Object.entries(links).forEach(([token, [url, label]]) => {
        content = content.replaceAll(token, `</strong><a href="${url}" target="_blank" rel="noopener noreferrer"><strong>${label}</strong></a><strong>`)
    })

    return `<p id="${marker}"><strong>${content}</strong></p>`
}

const stripSignature = (content = '') => content.replace(
    /\s*<p\b[^>]*>.*?<\/p>\s*/gis,
    (paragraph) => paragraph.includes(marker)
        || (paragraph.includes('t.me/NewsHub_Channel')
            && paragraph.includes('instagram.com/news_hub.kz')
            && paragraph.includes('kz.newshub.application')
            && paragraph.includes('id1604898976'))
            ? ''
            : paragraph,
)

export default {
    name: 'NewsHubPostToolsFormField',

    props: ['field', 'resourceId', 'resourceName'],

    data() {
        return {
            enabled: Boolean(this.field.enabled),
            template: this.field.value || this.field.defaultTemplate || '',
            editorOpen: false,
            previewOpen: false,
            preview: {
                title: '',
                summary: '',
                image: '',
                content: '',
            },
        }
    },

    computed: {
        signatureHtml() {
            return buildSignature(this.template, this.field.defaultTemplate)
        },
    },

    mounted() {
        this.field.fill = this.fill
    },

    methods: {
        fill(formData) {
            formData.append(this.field.attribute, this.template || this.field.defaultTemplate || '')
            formData.append('append_newshub_signature', this.enabled ? '1' : '0')
        },

        resetTemplate() {
            this.template = this.field.defaultTemplate || ''
        },

        visibleElement(selector) {
            return Array.from(document.querySelectorAll(selector)).find((element) => element.offsetParent !== null)
        },

        fieldValue(attribute) {
            return this.visibleElement(`[dusk="${attribute}"]`)?.value?.trim() || ''
        },

        editorContent() {
            const editors = window.tinymce?.editors || []
            const visibleEditor = editors.find((editor) => editor.getContainer?.()?.offsetParent !== null)

            if (visibleEditor) {
                return visibleEditor.getContent()
            }

            const iframe = this.visibleElement('.tox-edit-area__iframe')
            return iframe?.contentDocument?.body?.innerHTML || ''
        },

        imagePreview() {
            const images = Array.from(document.querySelectorAll('img[src^="blob:"], img[src^="data:image"]'))
                .filter((image) => image.offsetParent !== null && image.naturalWidth > 100)

            return images.length ? images[images.length - 1].src : ''
        },

        openPreview() {
            const cleanContent = stripSignature(this.editorContent()).trim()

            this.preview = {
                title: this.fieldValue('title'),
                summary: this.fieldValue('summary'),
                image: this.imagePreview(),
                content: this.enabled && cleanContent
                    ? `${cleanContent}\n${this.signatureHtml}`
                    : cleanContent,
            }
            this.previewOpen = true
        },
    },
}
</script>

<style>
.nh-post-tools {
    width: 100%;
    padding: 24px;
    color: rgba(var(--colors-gray-700), 1);
    background: rgba(var(--colors-primary-500), .07);
    border: 1px dashed rgba(var(--colors-primary-500), 1);
}

.nh-post-tools__top,
.nh-post-tools__actions,
.nh-post-tools__switch,
.nh-post-tools__editor-footer {
    display: flex;
    align-items: center;
}

.nh-post-tools__top {
    justify-content: space-between;
    gap: 20px;
}

.nh-post-tools__switch {
    gap: 12px;
    cursor: pointer;
}

.nh-post-tools__switch input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.nh-post-tools__switch-track {
    position: relative;
    flex: 0 0 42px;
    width: 42px;
    height: 24px;
    background: rgba(var(--colors-gray-300), 1);
    border-radius: 999px;
    transition: background .15s ease;
}

.nh-post-tools__switch-track::after {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    content: '';
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
    transition: transform .15s ease;
}

.nh-post-tools__switch input:checked + .nh-post-tools__switch-track {
    background: rgba(var(--colors-primary-500), 1);
}

.nh-post-tools__switch input:checked + .nh-post-tools__switch-track::after {
    transform: translateX(18px);
}

.nh-post-tools__switch strong,
.nh-post-tools__switch small {
    display: block;
}

.nh-post-tools__switch small {
    margin-top: 3px;
    color: rgba(var(--colors-gray-500), 1);
    font-size: 12px;
}

.nh-post-tools__actions {
    flex: 0 0 auto;
    gap: 8px;
}

.nh-post-tools__button,
.nh-post-tools__reset {
    padding: 9px 13px;
    font-weight: 600;
    color: rgba(var(--colors-gray-700), 1);
    background: rgba(var(--colors-gray-100), 1);
    border: 1px solid rgba(var(--colors-gray-300), 1);
    border-radius: 7px;
}

.nh-post-tools__button--primary {
    color: #fff;
    background: rgba(var(--colors-primary-500), 1);
    border-color: rgba(var(--colors-primary-500), 1);
}

.nh-post-tools__editor {
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid rgba(var(--colors-gray-300), 1);
}

.nh-post-tools__editor label {
    display: block;
    margin-bottom: 7px;
    font-weight: 700;
}

.nh-post-tools__editor textarea {
    width: 100%;
    padding: 12px;
    color: rgba(var(--colors-gray-900), 1);
    background: rgba(var(--colors-gray-50), 1);
    border: 1px solid rgba(var(--colors-gray-300), 1);
    border-radius: 7px;
}

.nh-post-tools__hint {
    margin-top: 5px;
    color: rgba(var(--colors-gray-500), 1);
    font-size: 12px;
}

.nh-post-tools__editor-footer {
    justify-content: space-between;
    gap: 24px;
    margin-top: 14px;
}

.nh-post-tools__reset {
    flex: 0 0 auto;
    font-size: 12px;
}

.nh-post-tools__signature-preview {
    max-width: 680px;
    font-size: 13px;
    text-align: right;
}

.nh-post-tools__signature-preview a,
.nh-post-preview__content a {
    color: rgba(var(--colors-primary-500), 1);
}

.nh-post-preview {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(5, 10, 24, .76);
    backdrop-filter: blur(5px);
}

.nh-post-preview__dialog {
    display: flex;
    width: min(1080px, 100%);
    max-height: calc(100vh - 32px);
    overflow: hidden;
    flex-direction: column;
    color: rgba(var(--colors-gray-900), 1);
    background: rgba(var(--colors-gray-50), 1);
    border-radius: 12px;
    box-shadow: 0 24px 70px rgba(0, 0, 0, .35);
}

.nh-post-preview__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid rgba(var(--colors-gray-200), 1);
}

.nh-post-preview__header strong,
.nh-post-preview__header small {
    display: block;
}

.nh-post-preview__header small {
    margin-top: 3px;
    color: rgba(var(--colors-gray-500), 1);
}

.nh-post-preview__header button {
    width: 36px;
    height: 36px;
    font-size: 28px;
    line-height: 1;
    color: rgba(var(--colors-gray-600), 1);
    background: transparent;
    border: 0;
}

.nh-post-preview__scroll {
    overflow-y: auto;
}

.nh-post-preview__article {
    width: min(820px, 100%);
    padding: 42px 30px 64px;
    margin: 0 auto;
}

.nh-post-preview__article h1 {
    margin: 0 0 22px;
    font-size: clamp(30px, 4vw, 48px);
    font-weight: 800;
    line-height: 1.12;
}

.nh-post-preview__summary {
    margin-bottom: 24px;
    color: rgba(var(--colors-gray-600), 1);
    font-size: 20px;
    line-height: 1.45;
}

.nh-post-preview__article img {
    display: block;
    width: 100%;
    max-height: 520px;
    margin-bottom: 26px;
    object-fit: contain;
    border-radius: 9px;
}

.nh-post-preview__content {
    font-size: 18px;
    line-height: 1.7;
}

@media (max-width: 900px) {
    .nh-post-tools__top,
    .nh-post-tools__editor-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .nh-post-tools__actions {
        flex-wrap: wrap;
    }

    .nh-post-tools__signature-preview {
        text-align: left;
    }
}

@media (max-width: 600px) {
    .nh-post-tools {
        padding: 18px;
    }

    .nh-post-tools__actions,
    .nh-post-tools__button {
        width: 100%;
    }

    .nh-post-tools__button {
        text-align: center;
    }

    .nh-post-preview {
        padding: 0;
    }

    .nh-post-preview__dialog {
        width: 100%;
        max-height: 100vh;
        min-height: 100vh;
        border-radius: 0;
    }

    .nh-post-preview__article {
        padding: 28px 20px 48px;
    }
}
</style>
