<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AdStat extends Resource
{
    public static $group = 'Разное';

    public static $model = \App\Models\AdStat::class;

    public static $title = 'date';

    public static $search = [];

    /**
     * Не показывать отдельным пунктом в меню — статистика видна внутри баннера.
     */
    public static $displayInNavigation = false;

    public static function label()
    {
        return __('Статистика по дням');
    }

    public static function singularLabel()
    {
        return __('День');
    }

    public function fields(Request $request)
    {
        return [
            Date::make(__('Дата'), 'date')
                ->displayUsing(fn ($v) => $v ? $v->format('d.m.Y') : null)
                ->sortable(),
            Number::make(__('Показы'), 'views')
                ->sortable(),
            Number::make(__('Клики'), 'clicks')
                ->sortable(),

            Text::make(__('Отчёт'), function () {
                $date = $this->date instanceof Carbon
                    ? $this->date->format('Y-m-d')
                    : (string) $this->date;

                $url = url("/report/ad-day/{$this->ad_id}/{$date}");

                return '<a href="' . $url . '" target="_blank" '
                    . 'style="color:#2563eb;font-weight:600;">Скачать PDF</a>';
            })->asHtml(),
        ];
    }

    /**
     * Статистика собирается автоматически — руками не создаём/не редактируем.
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function cards(Request $request)
    {
        return [];
    }

    public function filters(Request $request)
    {
        return [];
    }

    public function lenses(Request $request)
    {
        return [];
    }

    public function actions(Request $request)
    {
        return [];
    }
}
