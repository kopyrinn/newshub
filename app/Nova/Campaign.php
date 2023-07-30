<?php

namespace App\Nova;

use Alexwenzel\DependencyContainer\DependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Manogi\Tiptap\Tiptap;
use Alexwenzel\DependencyContainer\HasDependencies;
use App\Models\Package;
use App\Models\Region;
use App\Models\Role;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Panel;

class Campaign extends Resource
{
    use HasDependencies;

    public static $group = 'Разное';

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Campaign>
     */
    public static $model = \App\Models\Campaign::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $packages = Package::pluck('name', 'id');
        $roles = Role::pluck('name', 'id');
        $regions = Region::pluck('region_name_ru', 'id');

        return [
            ID::make()->sortable(),
            Text::make(__('Тема'), 'subject'),
            Tiptap::make(__('Сообщение'), 'body')
                ->rules('required')
                ->hideFromIndex()
                ->buttons([
                    'heading',
                    '|',
                    'italic',
                    'bold',
                    '|',
                    'link',
                    'code',
                    'strike',
                    'underline',
                    'highlight',
                    '|',
                    'bulletList',
                    'orderedList',
                    'br',
                    'blockquote',
                    '|',
                    'horizontalRule',
                    'hardBreak',
                    '|',
                    'table',
                    '|',
                    'image',
                    '|',
                    'textAlign',
                    '|',
                    'history',
                ])
                ->imageSettings([
                    'disk' => 'public',
                    'path' => 'campaigns/' . date('Y-m-d'),
                ])
                ->headingLevels([2, 3, 4]),

            Boolean::make(__('Активно'), 'is_active')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ,
            DateTime::make(__('Дата запуска'), 'start_at')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ,
            Number::make(__('Total'), 'total')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ,
            Number::make(__('Отправлено'), 'sent')
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ,
            Panel::make(__('Conditions'), [
                BooleanGroup::make(__('Roles'), 'roles')
                    ->options($roles->toArray())
                    ->default($roles->map(fn($item) => $item = true)->toArray()),

                BooleanGroup::make(__('Packages'), 'packages')
                    ->options(array_merge(
                        ['nopackage' => __('Бесплатный')],
                        $packages->toArray(),
                    ))
                    ->default(array_merge(
                        ['nopackage' => true],
                        $packages->map(fn($item) => $item = true)->toArray(),
                    )),

                Select::make(__('Состояние тарифа'), 'package_state')
                    ->hideFromIndex()
                    ->options([
                        'all' => __('Все'),
                        'active' => __('Активно'),
                        'inactive' => __('Неактивно'),
                    ])
                    ->default('all')
                    ->displayUsingLabels(),

                Boolean::make(__('Последняя активность'), 'has_activity')
                    ->hideFromIndex()
                    ->help(__('Используется для создания условия по дате последнего посещения пользователем')),
                DependencyContainer::make([
                    Select::make(__('Последняя активность <='), 'activity_gte')
                        ->options([
                            '1d' => __('<= 1 день'),
                            '1w' => __('<= 1 неделя'),
                            '1mo' => __('<= 1 месяц'),
                            '3mo' => __('<= 3 месяца'),
                            '6mo' => __('<= 6 месяцев'),
                            '1y' => __('<= 1 год'),
                            '5y' => __('<= 5 лет'),
                        ])
                        ->displayUsingLabels(),
                    Select::make(__('Последняя активность >='), 'activity_lte')
                        ->options([
                            '1d' => __('>= 1 день'),
                            '1w' => __('>= 1 неделя'),
                            '1mo' => __('>= 1 месяц'),
                            '3mo' => __('>= 3 месяца'),
                            '6mo' => __('>= 6 месяцев'),
                            '1y' => __('>= 1 год'),
                            '5y' => __('>= 5 лет'),
                        ])
                        ->displayUsingLabels(),
                ])->dependsOn('has_activity', 1),

                Boolean::make(__('Region'), 'has_regions')
                    ->hideFromIndex()
                    ->help(__('Используется для создания условия по регионам пользователей')),
                DependencyContainer::make([
                    BooleanGroup::make(__('Regions'), 'regions')
                        ->options($regions->toArray())
                        ->default($regions->map(fn($item) => $item = true)->toArray()),
                ])->dependsOn('has_regions', 1),
            ])
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            Actions\RunCampaign::make()
                ->canSee(function ($request) {
                    if ($request instanceof ActionRequest) {
                        return true;
                    }

                    return !$this->resource->is_active;
                })
                ->showInline(),
        ];
    }
}
