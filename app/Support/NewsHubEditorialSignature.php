<?php

namespace App\Support;

final class NewsHubEditorialSignature
{
    public const DEFAULT_TEMPLATE = 'Самые свежие новости экономики, политики и культуры на наших страницах в {telegram}, {instagram} и мобильных приложениях на {android} и {ios}.';

    private const MARKER = 'newshub-editorial-signature';

    private const TELEGRAM_URL = 'https://t.me/NewsHub_Channel';
    private const INSTAGRAM_URL = 'https://www.instagram.com/news_hub.kz/';
    private const ANDROID_URL = 'https://play.google.com/store/apps/details?id=kz.newshub.application';
    private const IOS_URL = 'https://apps.apple.com/kz/app/newshub-kz/id1604898976';

    public static function html(?string $template = null): string
    {
        $content = htmlspecialchars(
            self::normalizeTemplate($template),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8',
        );

        $links = [
            '{telegram}' => self::link(self::TELEGRAM_URL, 'Telegram'),
            '{instagram}' => self::link(self::INSTAGRAM_URL, 'Instagram'),
            '{android}' => self::link(self::ANDROID_URL, 'Android'),
            '{ios}' => self::link(self::IOS_URL, 'iOS'),
        ];

        $content = str_replace(
            array_keys($links),
            array_map(
                static fn (string $link) => '</strong>' . $link . '<strong>',
                array_values($links),
            ),
            nl2br($content, false),
        );

        return sprintf('<p id="%s"><strong>%s</strong></p>', self::MARKER, $content);
    }

    public static function apply(?string $content, bool $enabled, ?string $template = null): string
    {
        $content = self::remove($content);

        if (! $enabled || trim($content) === '') {
            return trim($content);
        }

        return rtrim($content) . PHP_EOL . self::html($template);
    }

    public static function normalizeTemplate(?string $template): string
    {
        $template = trim(strip_tags((string) $template));

        if ($template === '') {
            return self::DEFAULT_TEMPLATE;
        }

        return mb_substr($template, 0, 1000);
    }

    public static function contains(?string $content): bool
    {
        if (! $content) {
            return false;
        }

        if (str_contains($content, self::MARKER)) {
            return true;
        }

        return str_contains($content, self::TELEGRAM_URL)
            && str_contains($content, self::INSTAGRAM_URL)
            && str_contains($content, self::ANDROID_URL)
            && str_contains($content, self::IOS_URL);
    }

    public static function containsTranslations(array $translations): bool
    {
        foreach ($translations as $content) {
            if (self::contains($content)) {
                return true;
            }
        }

        return false;
    }

    public static function remove(?string $content): string
    {
        if (! $content) {
            return '';
        }

        return preg_replace_callback(
            '~\s*<p\b[^>]*>.*?</p>\s*~is',
            static fn (array $match) => self::contains($match[0]) ? '' : $match[0],
            $content,
        ) ?? $content;
    }

    private static function link(string $url, string $label): string
    {
        return sprintf('<a href="%s"><strong>%s</strong></a>', $url, $label);
    }
}
