<?php

namespace App\Nova;

use App\Models\Category;
use App\Models\Rubric;
use App\Support\NewsHubEditorialSignature;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Murdercode\TinymceEditor\TinymceEditor;

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
            Image::make(__('Image'), 'image_blur')
                ->exceptOnForms()
                // ->creationRules('required')
                ->path('img/blurry')
                ->nullable()
            ,
            Image::make(__('Image'), 'image')
                ->onlyOnForms()
                // ->creationRules('required')
                ->path('img/large')
                ->nullable()
            ,
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
                ,
                TinymceEditor::make(__('Content'), 'content')
                    ->rules(['required_lang:ru'])
                    ->fullWidth()
                    ->help(__('The content of the article.')),
                // Tiptap::make(__('Content'), 'content')
                //     ->rules('required_lang:ru')
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
                //         'path' => 'posts/' . date('Y-m-d'),
                //     ])
                //     ->headingLevels([2, 3, 4]),
            ]),
            Textarea::make('Текст подписи NewsHub', 'newshub_signature')
                ->onlyOnForms()
                ->alwaysShow()
                ->resolveUsing(function ($value) {
                    return $value ?: NewsHubEditorialSignature::DEFAULT_TEMPLATE;
                })
                ->help('Для кликабельных ссылок используйте маркеры {telegram}, {instagram}, {android} и {ios}.')
                ->canSee(function ($request) {
                    return $request->user()
                        && ($request->user()->isAdmin() || $request->user()->isModerator());
                }),
            Boolean::make('Добавить фирменную подпись NewsHub', 'append_newshub_signature')
                ->onlyOnForms()
                ->default(false)
                ->resolveUsing(function ($value, $model) {
                    return NewsHubEditorialSignature::containsTranslations(
                        $model->getTranslations('content'),
                    );
                })
                ->fillUsing(function (Request $request, $model, $attribute, $requestAttribute) {
                    $enabled = $request->boolean($requestAttribute);
                    $model->newshub_signature = $enabled
                        ? NewsHubEditorialSignature::normalizeTemplate($model->newshub_signature)
                        : null;

                    foreach ($model->getTranslations('content') as $locale => $content) {
                        $model->setTranslation(
                            'content',
                            $locale,
                            NewsHubEditorialSignature::apply(
                                $content,
                                $enabled,
                                $model->newshub_signature,
                            ),
                        );
                    }
                })
                ->help('Добавляет в конец материала ссылки на Telegram, Instagram, Android и iOS. Повторно подпись не дублируется.')
                ->canSee(function ($request) {
                    return $request->user()
                        && ($request->user()->isAdmin() || $request->user()->isModerator());
                }),
            BooleanGroup::make(__('Categories'), 'selected_categories')
                ->options(Category::all()->pluck('name', 'id')->toArray())
                // ->columns(4)
                ->rules('required')
                ->hideFromIndex()
            ,
            BooleanGroup::make(__('Rubrics'), 'selected_rubrics')
                ->options(Rubric::all()->pluck('name', 'id')->toArray())
                // ->columns(4)
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
            Select::make(__('PR'), 'article_type')
                ->hideFromIndex()
                ->options([
                    "promoted" => "Рекламный материал",
                    "partner" => "Партнерский материал",
                    "sponsored" => "Спонсорский материал",
                    "advertising" => "На правах рекламы",
                    "paid_sponsor" => "Оплачено спонсором",
                    "rights_pr" => "На правах PR",
                ])
                ->displayUsingLabels()
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
            // FileManager::make(__('Files'), 'files')
            //     ->hideFromIndex()
            //     ->multiple(true)
            //     ->limit(100),
            // ArrayFiles::make(__('Files'), 'files')
            //     ->disk('public')
            //     ->path('files')
            //     ->hideFromIndex()
            // ,
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
                // ->default(date('Y-m-d H:i:s'))
                ->hideFromIndex(),
            BelongsTo::make('User')
                ->searchable()
                ->sortable()
                ->default(auth()->user()->id)
                ->hideFromIndex()
            ,
            BelongsTo::make(__('Author'), 'author', User::class)
                ->searchable()
                ->sortable()
                ->default(auth()->user()->id)
            ,
            DateTime::make(__('Created At'), 'created_at')
                // ->withMeta(['value' => Carbon::now()->format('Y-m-d\TH:i:s.uP')])
                // ->format('DD.MM.YYYY HH:mm:ss')
                // ->default(gmdate('Y-m-d\TH:i:s.uP', strtotime('now')))
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
            Actions\ModeratePostAction::make()->showInline(),
        ];
    }
}
