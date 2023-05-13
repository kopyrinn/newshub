<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Silvanite\NovaToolPermissions\Role;

class User extends Resource
{
    public static $group = 'Пользователи';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Пользователи');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Пользователь');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Avatar::make('Avatar'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),
            
            Text::make('Lastname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Phone')
                ->hideFromIndex()
                ->sortable()
                ->rules('max:255'),

            Text::make('Address')
                ->hideFromIndex(),

            Textarea::make('Description')
                ->hideFromIndex(),

            Textarea::make('Requisites')
                ->hideFromIndex(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make('Email Verified At')
                ->hideFromIndex()
            ,

            Number::make('Balance')
                ->sortable()
            ,

            BelongsTo::make('UserCategory')
                ->hideFromIndex()
                ->nullable()
            ,

            BelongsTo::make(__('City'), 'city', City::class)
                ->hideFromIndex()
                ->nullable()
            ,

            BelongsTo::make('Package', 'package')
                ->sortable()
                ->nullable()
            ,

            Number::make('Package Press')
                ->hideFromIndex()
            ,

            Number::make('Package Events')
                ->hideFromIndex()
            ,

            Number::make('Package Vacancies')
                ->hideFromIndex()
            ,

            Number::make('Package Help')
                ->hideFromIndex()
            ,

            Number::make('Package Translate')
                ->hideFromIndex()
            ,

            Number::make('Package Pr')
                ->hideFromIndex()
            ,

            // Number::make('Package Styles')
            //     ->hideFromIndex()
            // ,

            DateTime::make('Package Expired At')
                //->hideFromIndex()
            ,

            BelongsToMany::make('Roles', 'roles', Role::class),
            HasMany::make(__('Balances'), 'balances', Balance::class),
            HasMany::make(__('Posts'), 'posts', Post::class),
            HasMany::make(__('Vacancies'), 'vacancies', Vacancy::class),
            HasMany::make(__('PollVote'), 'pollVotes', PollVote::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\UserRole,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
