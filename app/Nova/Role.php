<?php

namespace App\Nova;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
// use Benjaminhirsch\NovaSlugField\Slug;
use Laravel\Nova\Fields\BelongsToMany;
use App\Models\Role as RoleModel;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Slug;
// use Silvanite\NovaFieldCheckboxes\Checkboxes;
// use Benjaminhirsch\NovaSlugField\TextWithSlug;

class Role extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = RoleModel::class;

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
    public static $search = [];

    public static $with = [
        'users',
    ];

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

            Text::make(__('Name'), 'name')->sortable(),

            Slug::make(__('Slug'), 'slug')
                ->from('name')
                ->rules('required')
                ->creationRules('unique:roles')
                ->updateRules('unique:roles,slug,{{resourceId}}')
                ->sortable(),

            BooleanGroup::make(__('Permissions'), 'selected_permissions')->options(collect(array_keys(Gate::abilities()))
                ->mapWithKeys(function ($policy) {
                    return [
                        $policy => __($policy),
                    ];
                })
                ->sort()
                ->toArray()),

            Text::make(__('Users'), function () {
                return count($this->users);
            })->onlyOnIndex(),

            BelongsToMany::make(__('Users'), 'users', config('novatoolpermissions.userResource', 'App\Nova\User'))
                ->searchable(),
        ];
    }

    /**
     * Get the logical group associated with the resource.
     *
     * @return string
     */
    public static function group()
    {
        return __(config('novatoolpermissions.roleResourceGroup', static::$group));
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Roles');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Role');
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
        return [];
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
