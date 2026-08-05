(() => {
    if (window.__newsHubPostPreviewInstalled) {
        return
    }

    window.__newsHubPostPreviewInstalled = true

    const triggerSelector = '[data-news-hub-post-preview]'
    const signatureMarker = 'newshub-editorial-signature'
    const defaultSignature = 'Самые свежие новости экономики, политики и культуры на наших страницах в {telegram}, {instagram} и мобильных приложениях на {android} и {ios}.'
    const locales = ['ru', 'kk', 'en']
    const links = {
        '{telegram}': ['https://t.me/NewsHub_Channel', 'Telegram'],
        '{instagram}': ['https://www.instagram.com/news_hub.kz/', 'Instagram'],
        '{android}': ['https://play.google.com/store/apps/details?id=kz.newshub.application', 'Android'],
        '{ios}': ['https://apps.apple.com/kz/app/newshub-kz/id1604898976', 'iOS'],
    }

    let modal = null
    let previouslyFocused = null

    const isVisible = (element) => Boolean(
        element
        && element.getClientRects().length
        && window.getComputedStyle(element).visibility !== 'hidden'
    )

    const escapeHtml = (value) => String(value || '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;')

    const fieldControl = (attribute) => {
        const candidates = Array.from(document.querySelectorAll(
            `[dusk="${attribute}"], [name="${attribute}"], [id="${attribute}"], [id^="${attribute}-"]`,
        ))

        for (const candidate of candidates) {
            const control = candidate.matches('input, textarea, select')
                ? candidate
                : candidate.querySelector('input, textarea, select')

            if (control && isVisible(control)) {
                return control
            }
        }

        return candidates
            .map((candidate) => candidate.matches('input, textarea, select')
                ? candidate
                : candidate.querySelector('input, textarea, select'))
            .find(Boolean) || null
    }

    const activeLocale = () => locales.find((locale) => (
        isVisible(fieldControl(`translations_title_${locale}`))
        || isVisible(fieldControl(`translations_summary_${locale}`))
    )) || 'ru'

    const fieldValue = (attribute) => fieldControl(attribute)?.value?.trim() || ''

    const editorContent = (locale) => {
        const editorId = `translations_content_${locale}`
        const editors = Array.from(window.tinymce?.editors || [])
        const editor = window.tinymce?.get?.(editorId)
            || editors.find((item) => item.id === editorId)
            || editors.find((item) => isVisible(item.getContainer?.()))

        if (editor) {
            return editor.getContent()
        }

        return fieldValue(editorId)
    }

    const sanitizeContent = (content) => {
        const parsed = new DOMParser().parseFromString(`<body>${content || ''}</body>`, 'text/html')

        parsed.querySelectorAll('script, style, link, meta, base, object, embed').forEach((element) => element.remove())
        parsed.querySelectorAll('*').forEach((element) => {
            Array.from(element.attributes).forEach((attribute) => {
                if (attribute.name.toLowerCase().startsWith('on')) {
                    element.removeAttribute(attribute.name)
                }

                if (['href', 'src'].includes(attribute.name.toLowerCase())
                    && /^\s*javascript:/i.test(attribute.value)) {
                    element.removeAttribute(attribute.name)
                }
            })
        })

        return parsed.body.innerHTML
    }

    const stripSignature = (content) => {
        const container = document.createElement('div')
        container.innerHTML = sanitizeContent(content)
        container.querySelector(`#${signatureMarker}`)?.remove()

        Array.from(container.querySelectorAll('p')).forEach((paragraph) => {
            const html = paragraph.innerHTML
            const isLegacySignature = html.includes('t.me/NewsHub_Channel')
                && html.includes('instagram.com/news_hub.kz')
                && html.includes('kz.newshub.application')
                && html.includes('id1604898976')

            if (isLegacySignature) {
                paragraph.remove()
            }
        })

        return container.innerHTML.trim()
    }

    const signatureHtml = () => {
        let content = escapeHtml(fieldValue('newshub_signature') || defaultSignature).replaceAll('\n', '<br>')

        Object.entries(links).forEach(([token, [url, label]]) => {
            content = content.replaceAll(
                token,
                `</strong><a href="${url}" target="_blank" rel="noopener noreferrer"><strong>${label}</strong></a><strong>`,
            )
        })

        return `<p id="${signatureMarker}"><strong>${content}</strong></p>`
    }

    const signatureEnabled = () => Boolean(fieldControl('append_newshub_signature')?.checked)

    const readImage = () => new Promise((resolve) => {
        const input = fieldControl('image')
        const file = input?.files?.[0]

        if (file) {
            const reader = new FileReader()
            reader.addEventListener('load', () => resolve(String(reader.result || '')), { once: true })
            reader.addEventListener('error', () => resolve(''), { once: true })
            reader.readAsDataURL(file)
            return
        }

        const fieldRoot = input?.closest('[data-testid], .flex.border-b, .py-6') || input?.parentElement
        const scopedImage = fieldRoot
            ? Array.from(fieldRoot.querySelectorAll('img')).find((image) => isVisible(image))
            : null
        const uploadedImage = Array.from(document.querySelectorAll('img[src^="blob:"], img[src^="data:image"]'))
            .find((image) => isVisible(image) && image.naturalWidth > 80)

        resolve(scopedImage?.currentSrc || scopedImage?.src || uploadedImage?.currentSrc || uploadedImage?.src || '')
    })

    const buildDocument = ({ locale, title, summary, image, content }) => `<!doctype html>
        <html lang="${locale}">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <base target="_blank">
                <style>
                    :root { color-scheme: light; }
                    * { box-sizing: border-box; }
                    body { margin: 0; color: #181b2d; background: #f5f7fa; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
                    article { width: min(860px, calc(100% - 32px)); min-height: 100vh; padding: 48px 52px 72px; margin: 0 auto; background: #fff; }
                    .locale { display: inline-flex; padding: 5px 9px; margin-bottom: 18px; color: #2f88f6; background: #eaf3ff; border-radius: 999px; font-size: 12px; font-weight: 800; text-transform: uppercase; }
                    h1 { margin: 0 0 20px; font-size: clamp(30px, 5vw, 50px); line-height: 1.12; overflow-wrap: anywhere; }
                    .summary { margin: 0 0 28px; color: #667085; font-size: 20px; line-height: 1.5; }
                    .cover { display: block; width: 100%; max-height: 560px; margin: 0 0 30px; object-fit: contain; border-radius: 12px; }
                    .content { font-size: 18px; line-height: 1.72; overflow-wrap: anywhere; }
                    .content img, .content video { max-width: 100%; height: auto; }
                    .content iframe { max-width: 100%; }
                    .content a { color: #168de2; }
                    .content p { margin: 0 0 1.15em; }
                    .empty { padding: 24px; color: #98a2b3; text-align: center; border: 1px dashed #d0d5dd; border-radius: 10px; }
                    #${signatureMarker} { padding-top: 22px; margin-top: 36px; border-top: 1px solid #e4e7ec; }
                    @media (max-width: 640px) {
                        article { width: 100%; padding: 28px 20px 48px; }
                        .summary { font-size: 17px; }
                        .content { font-size: 16px; }
                    }
                </style>
            </head>
            <body>
                <article>
                    <span class="locale">${escapeHtml(locale)}</span>
                    <h1>${escapeHtml(title || 'Без заголовка')}</h1>
                    ${summary ? `<p class="summary">${escapeHtml(summary)}</p>` : ''}
                    ${image ? `<img class="cover" src="${escapeHtml(image)}" alt="">` : ''}
                    <div class="content">${content || '<div class="empty">Текст публикации пока не заполнен</div>'}</div>
                </article>
            </body>
        </html>`

    const ensureModal = () => {
        if (modal?.isConnected) {
            return modal
        }

        modal = document.createElement('div')
        modal.className = 'nh-post-preview-modal'
        modal.hidden = true
        modal.innerHTML = `
            <section class="nh-post-preview-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="nh-post-preview-title">
                <header class="nh-post-preview-modal__header">
                    <div>
                        <strong id="nh-post-preview-title">Предпросмотр поста</strong>
                        <small>Черновик ещё не сохранён</small>
                    </div>
                    <button type="button" class="nh-post-preview-modal__close" data-news-hub-post-preview-close aria-label="Закрыть">&times;</button>
                </header>
                <iframe class="nh-post-preview-modal__frame" title="Предпросмотр публикации" sandbox="allow-popups"></iframe>
            </section>`
        document.body.appendChild(modal)

        return modal
    }

    const closePreview = () => {
        if (!modal) {
            return
        }

        modal.hidden = true
        modal.querySelector('iframe')?.removeAttribute('srcdoc')
        document.documentElement.classList.remove('nh-post-preview-open')
        previouslyFocused?.focus?.()
        previouslyFocused = null
    }

    const openPreview = async (trigger) => {
        const locale = activeLocale()
        const cleanContent = stripSignature(editorContent(locale))
        const content = signatureEnabled() && cleanContent
            ? `${cleanContent}\n${signatureHtml()}`
            : cleanContent
        const image = await readImage()
        const currentModal = ensureModal()
        const frame = currentModal.querySelector('iframe')

        frame.srcdoc = buildDocument({
            locale,
            title: fieldValue(`translations_title_${locale}`),
            summary: fieldValue(`translations_summary_${locale}`),
            image,
            content,
        })

        previouslyFocused = trigger
        currentModal.hidden = false
        document.documentElement.classList.add('nh-post-preview-open')
        currentModal.querySelector('[data-news-hub-post-preview-close]')?.focus()
    }

    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest?.(triggerSelector)
        const closeButton = event.target.closest?.('[data-news-hub-post-preview-close]')

        if (trigger) {
            event.preventDefault()
            trigger.disabled = true

            try {
                await openPreview(trigger)
            } finally {
                trigger.disabled = false
            }

            return
        }

        if (closeButton || (modal && event.target === modal)) {
            closePreview()
        }
    })

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal && !modal.hidden) {
            closePreview()
        }
    })
})()
