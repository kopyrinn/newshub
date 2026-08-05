<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Field;

class NewsHubPostTools extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'news-hub-post-tools';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Indicates if the element should be shown on the detail view.
     *
     * @var bool
     */
    public $showOnDetail = false;

    public function enabled(bool $enabled): self
    {
        return $this->withMeta(['enabled' => $enabled]);
    }

    public function defaultTemplate(string $template): self
    {
        return $this->withMeta(['defaultTemplate' => $template]);
    }
}
