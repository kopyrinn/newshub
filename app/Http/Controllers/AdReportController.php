<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Services\AdReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdReportController extends Controller
{
    private const TEMP_DIRECTORY = 'tmp/ad-period-reports';

    public function __construct(private readonly AdReportService $reports) {}

    /**
     * PDF-отчёт по одному баннеру за один день.
     */
    public function day(Ad $ad, string $date)
    {
        $this->authorize('view', $ad);

        $carbon = Carbon::parse($date);

        return $this->reports
            ->dayPdf($ad, $carbon)
            ->download("banner-{$ad->id}-{$carbon->format('Y-m-d')}.pdf");
    }

    /**
     * PDF-отчёт по одному баннеру за выбранный период.
     */
    public function period(Request $request, Ad $ad)
    {
        $this->authorize('view', $ad);

        $validated = $request->validate([
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $from = Carbon::createFromFormat('Y-m-d', $validated['from'])->startOfDay();
        $to = Carbon::createFromFormat('Y-m-d', $validated['to'])->startOfDay();

        abort_if($from->diffInDays($to) > 365, 422, 'Максимальный период отчёта — 366 дней.');

        return $this->reports
            ->periodPdf($ad, $from, $to)
            ->download("banner-{$ad->id}-{$from->format('Y-m-d')}-{$to->format('Y-m-d')}.pdf");
    }

    /**
     * Скачать заранее сформированный Nova Action PDF без обновления карточки.
     */
    public function prepared(Request $request, string $token)
    {
        $disk = Storage::disk('local');
        $path = self::TEMP_DIRECTORY.'/'.$token.'.pdf';

        abort_unless($disk->exists($path), 404);

        $filename = basename((string) $request->query('name', 'banner-report.pdf'));
        if (! str_ends_with(strtolower($filename), '.pdf')) {
            $filename .= '.pdf';
        }

        return response()
            ->download($disk->path($path), $filename, ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }
}
