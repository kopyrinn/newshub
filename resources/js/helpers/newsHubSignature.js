export const DEFAULT_NEWSHUB_SIGNATURE = 'Самые свежие новости экономики, политики и культуры на наших страницах в {telegram}, {instagram} и мобильных приложениях на {android} и {ios}.'

const MARKER = 'newshub-editorial-signature'

const links = {
    '{telegram}': ['https://t.me/NewsHub_Channel', 'Telegram'],
    '{instagram}': ['https://www.instagram.com/news_hub.kz/', 'Instagram'],
    '{android}': ['https://play.google.com/store/apps/details?id=kz.newshub.application', 'Android'],
    '{ios}': ['https://apps.apple.com/kz/app/newshub-kz/id1604898976', 'iOS'],
}

const escapeHtml = (value) => String(value || '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;')

export const newsHubSignatureHtml = (template = DEFAULT_NEWSHUB_SIGNATURE) => {
    let content = escapeHtml(template.trim() || DEFAULT_NEWSHUB_SIGNATURE)
        .replaceAll('\n', '<br>')

    Object.entries(links).forEach(([token, [url, label]]) => {
        content = content.replaceAll(
            token,
            `</strong><a href="${url}" target="_blank" rel="noopener noreferrer"><strong>${label}</strong></a><strong>`,
        )
    })

    return `<p id="${MARKER}"><strong>${content}</strong></p>`
}

export const removeNewsHubSignature = (content = '') => content.replace(
    /\s*<p\b[^>]*>.*?<\/p>\s*/gis,
    (paragraph) => paragraph.includes(MARKER)
        || (paragraph.includes('t.me/NewsHub_Channel')
            && paragraph.includes('instagram.com/news_hub.kz')
            && paragraph.includes('kz.newshub.application')
            && paragraph.includes('id1604898976'))
        ? ''
        : paragraph,
)

export const applyNewsHubSignature = (content, enabled, template) => {
    const cleanContent = removeNewsHubSignature(content || '').trim()

    if (!enabled || !cleanContent) return cleanContent

    return `${cleanContent}\n${newsHubSignatureHtml(template)}`
}
