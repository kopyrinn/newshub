<?php

namespace App\Nova\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadAdPeriodReport extends Action
{
    public $name = 'Скачать PDF за период';

    public $confirmButtonText = 'Скачать PDF';

    public $cancelButtonText = 'Отмена';

    public $confirmText = 'Выберите период для отчёта по показам и кликам баннера.';

    /**
     * Generate a download response for one banner.
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() !== 1) {
            return Action::danger('Для отчёта необходимо выбрать один баннер.');
        }

        $from = Carbon::parse($fields->from)->startOfDay();
        $to = Carbon::parse($fields->to)->startOfDay();

        if ($from->gt($to)) {
            return Action::danger('Дата начала не может быть позже даты окончания.');
        }

        if ($from->diffInDays($to) > 365) {
            return Action::danger('Максимальный период отчёта — 366 дней.');
        }

        $ad = $models->first();
        $filename = "banner-{$ad->id}-{$from->format('Y-m-d')}-{$to->format('Y-m-d')}.pdf";
        $url = route('report.ad.period', [
            'ad' => $ad->id,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ]);

        return Action::download($url, $filename);
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request)
    {
        return [
            Date::make('Начало периода', 'from')
                ->default(now()->startOfMonth()->toDateString())
                ->rules('required', 'date'),
            Date::make('Конец периода', 'to')
                ->default(now()->toDateString())
                ->rules('required', 'date', 'after_or_equal:from'),
        ];
    }
}
