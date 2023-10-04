<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Manogi\Tiptap\Tiptap;
use Murdercode\TinymceEditor\TinymceEditor;

class Poll extends Resource
{
    public static $group = 'Опросы';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Poll::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'question';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'question', 'description',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Опросы');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Опрос');
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
            ID::make(__('ИД'), 'id')
                ->rules('required')
                ->sortable()
            ,
            Boolean::make(__('Активно'), 'is_active')
                ->default(1)
                ->sortable()
            ,
            Image::make(__('Изображение'), 'image_blur')
                ->exceptOnForms()
                ->path('polls')
            ,
            Image::make(__('Изображение'), 'image')
                ->onlyOnForms()
                ->path('polls')
            ,
            Text::make(__('Вопрос'), 'question')
                ->sortable()
            ,
            Slug::make(__('URL'), 'slug')
                ->from('question')
                ->sortable()
            ,
            TinymceEditor::make(__('Description'), 'description')
                ->hideFromIndex()
                ->fullWidth(),
            // Tiptap::make(__('Description'), 'description')
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
            //         'path' => 'polls/' . date('Y-m-d'),
            //     ])
            //     ->headingLevels([2, 3, 4]),
            DateTime::make(__('Дата начала'), 'start_at')
                ->sortable()
            ,
            DateTime::make(__('Дата завершения'), 'expired_at')
                ->sortable()
            ,
            Boolean::make(__('Скрыть после завершения'), 'is_hide_after_expired')
                ->default(0)
                ->hideFromIndex()
                ->help('Скрыть участников (кроме победителя) после завершения голосования')
                ->sortable()
            ,
            HasMany::make(__('Участники'), 'requests', PollRequest::class),
            HasMany::make(__('Голоса'), 'votes', PollVote::class)
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
