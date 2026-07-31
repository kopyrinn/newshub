<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\AdStat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AdReportService
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

    public function dayPdf(Ad $ad, Carbon $date)
    {
        $stat = AdStat::where('ad_id', $ad->id)
            ->whereDate('date', $date->toDateString())
            ->first();

        $views = $stat->views ?? 0;
        $clicks = $stat->clicks ?? 0;

        return Pdf::loadView('reports.ad-day', [
            'ad' => $ad,
            'locationLabel' => $this->locationLabel($ad->location),
            'date' => $date,
            'views' => $views,
            'clicks' => $clicks,
            'ctr' => $views > 0 ? round($clicks / $views * 100, 2) : 0,
            'generatedAt' => Carbon::now(),
            'bannerDataUri' => $this->bannerDataUri($ad->image),
        ]);
    }

    public function periodPdf(Ad $ad, Carbon $from, Carbon $to)
    {
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

        return Pdf::loadView('reports.ad-period', [
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
