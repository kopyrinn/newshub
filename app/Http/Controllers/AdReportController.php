<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdStat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdReportController extends Controller
{
    /**
     * Человекочитаемые названия позиций баннера (совпадают с админкой).
     */
    private const LOCATIONS = [
        'header' => 'БГВ-1',
        'post.view' => 'В новости',
        'category.view' => 'В категории новости',
        'sidebar.view' => 'БГП-1',
        'sidebar2.view' => 'БГП-2',
        'sidebar_alt' => 'БГП-3',
    ];

    /**
     * PDF-отчёт по одному баннеру за один день.
     */
    public function day(Ad $ad, string $date)
    {
        $this->authorize('view', $ad);

        $carbon = Carbon::parse($date);

        $stat = AdStat::where('ad_id', $ad->id)
            ->whereDate('date', $carbon->toDateString())
            ->first();

        $views = $stat->views ?? 0;
        $clicks = $stat->clicks ?? 0;

        $pdf = Pdf::loadView('reports.ad-day', [
            'ad' => $ad,
            'locationLabel' => $this->locationLabel($ad->location),
            'date' => $carbon,
            'views' => $views,
            'clicks' => $clicks,
            'ctr' => $views > 0 ? round($clicks / $views * 100, 2) : 0,
            'generatedAt' => Carbon::now(),
            'bannerDataUri' => $this->bannerDataUri($ad->image),
        ]);

        return $pdf->download("banner-{$ad->id}-{$carbon->format('Y-m-d')}.pdf");
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

        $stats = AdStat::where('ad_id', $ad->id)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->get()
            ->keyBy(fn (AdStat $stat) => $stat->date->toDateString());

        $rows = collect(CarbonPeriod::create($from, $to))->map(function ($date) use ($stats) {
            $stat = $stats->get($date->toDateString());
            $views = $stat?->views ?? 0;
            $clicks = $stat?->clicks ?? 0;

            return [
                'date' => Carbon::instance($date),
                'views' => $views,
                'clicks' => $clicks,
                'ctr' => $views > 0 ? round($clicks / $views * 100, 2) : 0,
            ];
        });

        $views = $rows->sum('views');
        $clicks = $rows->sum('clicks');

        $pdf = Pdf::loadView('reports.ad-period', [
            'ad' => $ad,
            'locationLabel' => $this->locationLabel($ad->location),
            'from' => $from,
            'to' => $to,
            'rows' => $rows,
            'views' => $views,
            'clicks' => $clicks,
            'ctr' => $views > 0 ? round($clicks / $views * 100, 2) : 0,
            'generatedAt' => Carbon::now(),
            'bannerDataUri' => $this->bannerDataUri($ad->image),
        ])->setPaper('a4', 'portrait');

        return $pdf->download(
            "banner-{$ad->id}-{$from->format('Y-m-d')}-{$to->format('Y-m-d')}.pdf"
        );
    }

    private function locationLabel(?string $location): string
    {
        if ($location && str_starts_with($location, 'home.')) {
            return 'БГЛ: '.substr($location, 5);
        }

        return self::LOCATIONS[$location] ?? ($location ?? '—');
    }

    private function bannerDataUri(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($image)) {
                return null;
            }

            $mimeType = $disk->mimeType($image);

            if (! in_array($mimeType, ['image/gif', 'image/jpeg', 'image/png', 'image/webp'], true)) {
                return null;
            }

            return 'data:'.$mimeType.';base64,'.base64_encode($disk->get($image));
        } catch (Throwable) {
            return null;
        }
    }
}
