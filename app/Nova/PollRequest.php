<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class PollRequest extends Resource
{
    public static $group = 'Опросы';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\PollRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'name', 'phone', 'position', 'email',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Участники');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Участник');
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
            ID::make(__('Ид'), 'id')
                ->rules('required')
                ->sortable()
            ,
            Image::make(__('Фото'), 'photo')
                ->path('requests')
            ,
            BelongsTo::make(__('Пользователь'), 'user', User::class)
                ->searchable()
                ->sortable()
            ,
            BelongsTo::make(__('Опрос'), 'poll', Poll::class)
                ->searchable()
                ->sortable()
            ,
            Select::make(__('Статус'), 'status')
                ->default('wait')
                ->options([
                    'wait' => 'Модерация',
                    'done' => 'Одобрено',
                    'reject' => 'Отклонено',
                ])
                ->onlyOnForms()
            ,
            Badge::make(__('Статус'), 'status')
                ->map([
                    'wait' => 'warning',
                    'done' => 'success',
                    'reject' => 'danger',
                ])
                ->labels([
                    'wait' => 'Модерация',
                    'done' => 'Одобрено',
                    'reject' => 'Отклонено',
                ])
                ->hideWhenCreating()
                ->hideWhenUpdating()
            ,
            Text::make(__('ФИО'), 'name')
                ->sortable()
            ,
            Text::make(__('Телефон'), 'phone')
                ->hideFromIndex()
                ->sortable()
            ,
            Text::make(__('Должность'), 'position')
                ->sortable()
            ,
            Text::make(__('Email'), 'email')
                ->hideFromIndex()
                ->sortable()
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
