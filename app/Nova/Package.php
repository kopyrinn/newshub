<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;

class Package extends Resource
{
    use TranslatableTabToRowTrait;

    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Package::class;

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
    public static $search = [];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Тарифы');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Тариф');
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
                Text::make(__('Name'), 'name')
                    ->rules('required_lang:ru')
                    ->sortable()
                ,
                Trix::make(__('Content'), 'content')
                    ->rules('required_lang:ru')
                    ->hideFromIndex()
                    ->sortable()
                ,
            ]),
            Slug::make(__('Slug'), 'slug')
                ->rules('required')
                ->sortable()
            ,
            Number::make(__('Price 1 Month'), 'price_1')
                ->rules('required')
                ->sortable()
            ,
            Number::make(__('Price 3 Month'), 'price_3')
                ->rules('required')
                ->hideFromIndex()
                ->sortable()
            ,
            Number::make(__('Price 6 Month'), 'price_6')
                ->rules('required')
                ->hideFromIndex()
                ->sortable()
            ,
            Number::make(__('Price 12 Month'), 'price_12')
                ->rules('required')
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
