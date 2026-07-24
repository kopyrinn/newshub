<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Slug;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Murdercode\TinymceEditor\TinymceEditor;

class Page extends Resource
{
    use TranslatableTabToRowTrait;

    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Страницы');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Страница');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('Id'), 'id')
                ->rules('required')
                ->sortable()
            ,
            NovaTabTranslatable::make([
                Text::make(__('Title'), 'title')
                    ->rules('required_lang:ru')
                    ->sortable()
                ,
                Textarea::make(__('Description'), 'description')
                    ->hideFromIndex()
                ,
                TinymceEditor::make(__('Content'), 'page_content')
                    ->hideFromIndex()
                    ->rules(['required_lang:ru'])
                    ->fullWidth(),
                // Tiptap::make(__('Content'), 'page_content')
                //     ->rules('required_lang:ru')
                //     ->hideFromIndex()
                //     ->buttons([
                //         'heading',
                //         '|',
                //         'italic',
                //         'bold',
                //         '|',
                //         'link',
                //         'code',
                //         'strike',
                //         'underline',
                //         'highlight',
                //         '|',
                //         'bulletList',
                //         'orderedList',
                //         'br',
                //         'blockquote',
                //         '|',
                //         'horizontalRule',
                //         'hardBreak',
                //         '|',
                //         'table',
                //         '|',
                //         'image',
                //         '|',
                //         'textAlign',
                //         '|',
                //         'history',
                //     ])
                //     ->imageSettings([
                //         'disk' => 'public',
                //         'path' => 'pages/' . date('Y-m-d'),
                //     ])
                //     ->headingLevels([2, 3, 4]),
            ]),
            Slug::make(__('Slug'), 'slug')
                ->rules('required')
                ->sortable()
            ,
            Boolean::make(__('Show On Menu'), 'show_on_menu')
                ->hideFromIndex()
            ,
            Boolean::make(__('Visibility'), 'visibility')
                ->sortable()
            ,
            Text::make(__('Keywords'), 'keywords')
                ->hideFromIndex()
            ,
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
