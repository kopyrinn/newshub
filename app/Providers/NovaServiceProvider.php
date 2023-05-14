<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Panel;
use Laravel\Nova\NovaApplicationServiceProvider;
use Silvanite\NovaToolPermissions\NovaToolPermissions;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Image;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \OptimistDigital\NovaSettings\NovaSettings::addSettingsFields([
            Panel::make('Основные', [
                Text::make('Заголовок', 'title'),
                Text::make('Описание', 'description'),
            ]),
            Panel::make('Цены', [
                Number::make('Стоимость публикации вакансии', 'vacancy_price')->step(0.01),
                Number::make('Стоимость публикации в большой слайдер', 'big_slider_price')->step(0.01),
                Number::make('Стоимость публикации в малый слайдер', 'small_slider_price')->step(0.01),
                Number::make('Стоимость публикации поста с цветной карточкой', 'style_card_price')->step(0.01),
            ]),
            Panel::make('Социальные сети', [
                Text::make('Facebook Url', 'facebook_url'),
                Text::make('Twitter Url', 'twitter_url'),
                Text::make('Instagram Url', 'instagram_url'),
                Text::make('Vk Url', 'vk_url'),
                Text::make('Telegram Url', 'telegram_url'),
                Text::make('Youtube Url', 'youtube_url'),
            ]),
            Panel::make('Логотипы', [
                Image::make('Лого KZ', 'logo_kk'),
                Image::make('Лого RU', 'logo_ru'),
                Image::make('Лого EN', 'logo_en'),
            ]),
        ]);

        parent::boot();
        Nova::style('custom', public_path('assets/css/custom_nova.css'));
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            // new \Tightenco\NovaGoogleAnalytics\PageViewsMetric,
            // new \Tightenco\NovaGoogleAnalytics\VisitorsMetric,
            // new \Tightenco\NovaGoogleAnalytics\OneDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\SevenDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\FourteenDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\TwentyEightDayActiveUsersMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionsByDeviceMetric,
            // new \Tightenco\NovaGoogleAnalytics\SessionsByCountryMetric,
            // new \Tightenco\NovaGoogleAnalytics\ReferrersList,
            // new \Tightenco\NovaGoogleAnalytics\MostVisitedPagesCard,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            // new \Appstract\NovaHorizon\Dashboard,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaToolPermissions(),
            (new \MadWeb\NovaTelescopeLink\TelescopeLink)->canSee(function ($request) {
                return auth()->user()->isAdmin();
            }),
            (new \OptimistDigital\NovaSettings\NovaSettings())->canSee(function ($request) {
                return auth()->user()->isAdmin();
            }),
            (new \Infinety\Filemanager\FilemanagerTool())->canSee(function ($request) {
                return auth()->user()->isAdmin();
            }),
            (new \Cloudstudio\ResourceGenerator\ResourceGenerator())->canSee(function ($request) {
                return auth()->user()->isAdmin();
            }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
