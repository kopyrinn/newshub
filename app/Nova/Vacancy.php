<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;

class Vacancy extends Resource
{
    use TranslatableTabToRowTrait;

    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Vacancy::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'job_title';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'job_title', 'place_work', 'requiremets',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Вакансии');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Вакансия');
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
            BelongsTo::make('User')
                ->rules('required')
                ->searchable()
                ->sortable()
                ->default(auth()->user()->id)
            ,
            Boolean::make(__('Status'), 'status')
                ->default(0)
                ->sortable()
            ,
            NovaTabTranslatable::make([
                Textarea::make(__('Job Title'), 'job_title')
                    ->rules('required_lang:ru')
                    ->sortable()
                ,
                Textarea::make(__('Requiremets'), 'requiremets')
                    ->hideFromIndex()
                    ->sortable()
                ,
                Textarea::make(__('Task'), 'task')
                    ->hideFromIndex()
                    ->sortable()
                ,
                Textarea::make(__('Conditionsm'), 'conditionsm')
                    ->hideFromIndex()
                    ->sortable()
                ,
            ]),
            Text::make(__('Email Jobseeker'), 'email_jobseeker')
                ->rules('required')
                ->hideFromIndex()
                ->sortable()
            ,
            // Text::make(__('Employment Type'), 'employment_type')
            //     ->rules('required')
            //     ->sortable()
            // ,
            // Text::make(__('Salary Max'), 'salary_max')
            //     ->rules('required')
            //     ->sortable()
            // ,
            // Number::make(__('Salary Min'), 'salary_min')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Place Work'), 'place_work')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Office Image'), 'office_image')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Additional Text'), 'additional_text')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Company Name'), 'company_name')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Email Notification'), 'email_notification')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Post Time'), 'post_time')
            //     ->rules('required')
            //     ->hideFromIndex()
            //     ->sortable()
            // ,
            // Text::make(__('Town Name'), 'town_name')
            //     ->rules('required')
            //     ->sortable()
            // ,
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
