<?php

return [
    'cloudChannel' => '6', // 5 or 6

    'apiKey' => env('TINYMCE_API_KEY', ''),

    /**
     * The default skin to use.
     */
    'skin' => 'oxide-dark',

    /**
     * The default options to send to the editor.
     * See https://www.tiny.cloud/docs/configure/ for all available options (check for 5 or 6 version).
     */
    'init' => [
        'menubar' => false,
        'autoresize_bottom_margin' => 40,
        'branding' => false,
        'image_caption' => true,
        'paste_as_text' => true,
        'autosave_interval' => '20s',
        'autosave_retention' => '30m',
        'browser_spellcheck' => true,
        'relative_urls' => false,
        'convert_urls' => false,
        'contextmenu' => 'link image table',
        'images_upload_url' => '/api/v2/upload', // Uncomment this line if you want to enable images upload
    ],
    'plugins' => [
        'advlist',
        'anchor',
        'autolink',
        'autosave',
        'fullscreen',
        'lists',
        'link',
        'image',
        'media',
        'table',
        'code',
        'wordcount',
        'autoresize',
    ],
    'toolbar' => [
        'undo redo restoredraft | h2 h3 h4 |
                 bold italic underline strikethrough blockquote removeformat |
                 align bullist numlist outdent indent | image link media anchor table | code fullscreen spoiler',
    ],

    /**
     * Extra configurations for the editor.
     */
    'extra' => [
        'upload_images' => [
            'enabled' => true, // Set true for enable images local upload
            'folder' => 'images',
            'maxSize' => 20480, // KB
        ],
    ],
];
