<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | Here you can define the options that are passed to all NovaTinyMCE
    | fields by default.
    |
    */

    'default_options' => [
        'content_css' => '/vendor/tinymce/skins/ui/oxide/content.min.css',
        'skin_url' => '/vendor/tinymce/skins/ui/oxide',
        'path_absolute' => '/',
        'plugins' => [
            'lists preview hr anchor pagebreak image media link wordcount fullscreen directionality paste textpattern'
        ],
        'toolbar' => 'undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | image media link | bullist numlist outdent indent',
        'relative_urls' => false,
        'use_lfm' => true,
        'lfm_url' => 'filemanager'
    ],
];
