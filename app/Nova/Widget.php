<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

class Widget extends Resource
{
    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Widget::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'id';

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
        return __('Виджеты');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Виджет');
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
            Select::make(__('Location'), 'location')
                ->rules('required')
                ->sortable()
                ->options([
                    'home_with_sidebar' => __('Home With Sidebar'),
                    'home_full_width' => __('Home Full Width'),
                ])
                ->displayUsingLabels()
            ,
            Select::make(__('View'), 'view')
                ->rules('required')
                ->sortable()
                ->options([
                    'small' => __('Small (10 Cards)'),
                    'medium' => __('2 Large And 10 Small (12 Cards)'),
                    'medium_alt' => __('1 Large And 5 Small (6 Cards)'),
                    'large' => __('Large (2 Cards)'),
                    'simple' => __('Simple posts, one per line (3 Lines)'),
                ])
                ->default('medium')
                ->displayUsingLabels()
            ,
            BelongsTo::make('Category')
                ->sortable()
            ,
            BelongsTo::make('Rubric')
                ->sortable()
                ->nullable()
            ,
            Number::make(__('Position'), 'position')
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
