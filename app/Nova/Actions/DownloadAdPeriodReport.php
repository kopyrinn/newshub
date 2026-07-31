<?php

namespace App\Nova\Actions;

use App\Services\AdReportService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadAdPeriodReport extends Action
{
    private const TEMP_DIRECTORY = 'tmp/ad-period-reports';

    public $name = 'Скачать PDF за период';

    public $confirmButtonText = 'Сформировать PDF';

    public $cancelButtonText = 'Отмена';

    public $confirmText = 'Выберите период. Во время формирования будет показан индикатор, затем скачивание начнётся автоматически.';

    public $withoutActionEvents = true;

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
        $token = (string) Str::uuid();
        $path = self::TEMP_DIRECTORY.'/'.$token.'.pdf';

        $this->deleteExpiredReports();

        Storage::disk('local')->put(
            $path,
            app(AdReportService::class)->periodPdf($ad, $from, $to)->output()
        );

        $url = URL::temporarySignedRoute('report.ad.period.prepared', now()->addMinutes(10), [
            'token' => $token,
            'name' => $filename,
        ]);

        return Action::redirect($url);
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

    private function deleteExpiredReports(): void
    {
        $disk = Storage::disk('local');
        $expiresBefore = now()->subDay()->getTimestamp();

        foreach ($disk->files(self::TEMP_DIRECTORY) as $path) {
            if ($disk->lastModified($path) < $expiresBefore) {
                $disk->delete($path);
            }
        }
    }
}
