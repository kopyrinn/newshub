export const sanitizePageContent = (slug, content) => {
    if (slug !== 'ads' || typeof content !== 'string') {
        return content
    }

    return content.replace(
        /<div\b[^>]*>(?:(?!<\/div>)[\s\S])*adv\.newshub\.kz(?:(?!<\/div>)[\s\S])*<\/div>\s*/giu,
        ''
    )
}
