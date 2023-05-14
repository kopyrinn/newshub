<?php

namespace App\Nova;

use App\Models\Category;
use App\Models\Rubric;
use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
// use Fourstacks\NovaCheckboxes\Checkboxes;
use Silvanite\NovaFieldCheckboxes\Checkboxes;
use Halimtuhu\ArrayFiles\ArrayFiles;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Trix;
use Infinety\Filemanager\FilemanagerField;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use OptimistDigital\MultiselectField\Multiselect;

class Post extends Resource
{
    use TranslatableTabToRowTrait;

    public static $group = 'Блог';
    public static $trafficCop = false;

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \App\Models\Post::class;

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
    public static $search = [
        'title', 'keywords', 'content',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Посты');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Пост');
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
            Boolean::make(__('Status'), 'status')
                ->default(1)
                ->sortable()
            ,
            Image::make(__('Image'), 'image')
                ->creationRules('required')
                ->path('img/large')
                ->nullable()
            ,
            // FilemanagerField::make(__('Image'), 'image')
            //     ->folder('posts')
            //     ->displayAsImage()
            // ,
            Text::make(__('Image Caption'), 'image_caption')
                // ->creationRules('required')
                ->hideFromIndex()
            ,
            NovaTabTranslatable::make([
                Text::make(__('Title'), 'title')
                    // ->rules('required_lang:ru')
                    ->sortable()
                    ->displayUsing(function ($name) {
                        return \Str::limit($name, 35);
                    })
                ,
                Textarea::make(__('Summary'), 'summary')
                    // ->rules('required_lang:ru')
                ,
                NovaTinyMCE::make(__('Content'), 'content')
                    // ->rules('required_lang:ru')
                    ->options([
                        'height' => '400'
                    ])
                    ->hideFromIndex()
                ,
            ]),
            Checkboxes::make(__('Categories'), 'selected_categories')
                ->options(Category::all()->pluck('name', 'id')->toArray())
                ->columns(4)
                ->rules('required')
                ->hideFromIndex()
            ,
            Checkboxes::make(__('Rubrics'), 'selected_rubrics')
                ->options(Rubric::all()->pluck('name', 'id')->toArray())
                ->columns(4)
                ->hideFromIndex()
            ,
            Text::make(__('Keywords'), 'keywords')
                ->hideFromIndex()
            ,
            Boolean::make(__('Is Slider'), 'is_slider')
                ->hideFromIndex()
            ,
            Boolean::make(__('Is Featured'), 'is_featured')
                ->hideFromIndex()
            ,
            Boolean::make(__('Is Recommended'), 'is_recommended')
                ->hideFromIndex()
            ,
            Boolean::make(__('Is Breaking'), 'is_breaking')
                ->hideFromIndex()
            ,
            Boolean::make(__('Is Styled'), 'is_styled')
                ->default(0)
                ->hideFromIndex()
            ,
            Select::make(__('Style Color'), 'style_color')
                ->hideFromIndex()
                ->options([
                    'success-light text-success' => __("Success Light"),
                    'info-light text-info' => __("Info Light"),
                    'warning-light text-warning' => __("Warning Light"),
                    'danger-light text-danger' => __("Danger Light"),
                    'gray-light text-gray-darker' => __("Gray Light"),
                    'success text-white' => __("Success"),
                    'info text-white' => __("Info"),
                    'warning text-white' => __("Warning"),
                    'danger text-white' => __("Danger"),
                    'muted text-white' => __("Muted"),
                    'primary-darker text-white' => __("Primary Darker"),
                    'primary-dark text-white' => __("Primary Dark"),
                    'primary text-white' => __("Primary"),
                    'primary-light text-white' => __("Primary Light"),
                    'primary-lighter text-white' => __("Primary Lighter"),
                ])
                ->default('success-light text-success')
                ->displayUsingLabels()
            ,
            ArrayFiles::make(__('Files'), 'files')
                ->disk('public')
                ->path('files')
                ->hideFromIndex()
            ,
            Boolean::make(__('To Fcm'), 'to_fcm')
                ->default(0)
                ->hideFromIndex()
            ,
            Boolean::make(__('To Telegram'), 'to_telegram')
                ->default(0)
                ->hideFromIndex()
            ,
            // Text::make(__('Event Place'), 'place')
            //     ->hideFromIndex()
            // ,
            // Text::make(__('Event Price'), 'price')
            //     ->hideFromIndex()
            // ,
            DateTime::make(__('Event Date'), 'event_date')
                ->default(date('Y-m-d H:i:s'))
                ->hideFromIndex(),
            BelongsTo::make('User')
                ->searchable()
                ->sortable()
                ->default(auth()->user()->id)
            ,
            BelongsTo::make(__('Author'), 'author', User::class)
                ->searchable()
                ->sortable()
                ->default(auth()->user()->id)
            ,
            DateTime::make(__('Created At'), 'created_at')
                ->default(date('Y-m-d H:i:s')),
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
        return [
            Actions\HandlePostTranslation::make()
                ->exceptOnIndex()
                ->canSee(function ($request) {
                    if ($request instanceof ActionRequest) {
                        return true;
                    }

                    if ($this->resource->user) {
                        return $this->resource->user->packageActive() && $this->resource->user->package_translate;
                    }

                    return true;
                }),
            Actions\ModeratePostAction::make()->showOnTableRow(),
        ];
    }
}
