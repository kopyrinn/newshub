<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;

class Ad extends Resource
{
    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Ad::class;

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
        return __('Баннеры');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return  string
    */
    public static function singularLabel()
    {
        return __('Баннер');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function fields(Request $request)
    {
        $locations = [
            'header' => 'БГВ-1',
            //'post.new' => 'В форме добавления новости',
            //'post.edit' => 'В форме редактирования новости',
            'post.view' => 'В новости',
           // 'page.view' => 'На статичной странице',
            //'vacancy.new' => 'В форме добавления вакансии',
            //'vacancy.view' => 'В вакансии',
            //'packages.view' => 'На странице услуг',
            'category.view' => 'В категории новости',
            //'rubric.view' => 'На страницах рубрик',
            'sidebar.view' => 'БГП-1',
            'sidebar2.view' => 'БГП-2',
            'sidebar_alt' => 'БГП-3',
        ];

        foreach (\App\Models\Category::pluck('name', 'slug') as $slug => $name) {
            $locations["home.{$slug}"] = "БГЛ: {$name}";
        }

        return [
            ID::make(__('Id'), 'id')
                ->rules('required')
                ->sortable()
            ,
            Select::make(__('Location'), 'location')
                ->rules('required')
                ->sortable()
                ->options($locations)
                ->displayUsingLabels()
            ,
            Text::make(__('Url'), 'url')
                ->rules('required')
                ->sortable()
            ,
            Number::make(__('Clicks'), 'clicks')
                ->sortable()
            ,
            Number::make(__('Views'), 'views')
                ->sortable()
            ,
            Image::make(__('Image'), 'image')
                ->rules('required')
                ->path('ads')
            ,
            DateTime::make(__('Expired At'), 'expired_at')
                ->sortable()
            ,
            HasMany::make(__('Статистика по дням'), 'stats', \App\Nova\AdStat::class),
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
