<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\AdStat;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Throwable;

class AdReportService
{
    private const BANNER_CACHE_DIRECTORY = 'cache/ad-report-banners';

    private const BANNER_CACHE_VERSION = 'jpeg-800x400-q65-v1';

    private const BANNER_MAX_HEIGHT = 400;

    private const BANNER_MAX_WIDTH = 800;

    private const BANNER_QUALITY = 65;

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

            $cacheDisk = Storage::disk('local');
            $cacheKey = hash('sha256', implode('|', [
                self::BANNER_CACHE_VERSION,
                $image,
                $disk->lastModified($image),
                $disk->size($image),
            ]));
            $cachePath = self::BANNER_CACHE_DIRECTORY.'/'.$cacheKey.'.jpg';

            if (! $cacheDisk->exists($cachePath)) {
                $manager = new ImageManager(['driver' => 'gd']);
                $source = $manager->make($disk->path($image))->orientate();

                $scale = min(
                    self::BANNER_MAX_WIDTH / $source->width(),
                    self::BANNER_MAX_HEIGHT / $source->height(),
                    1
                );

                if ($scale < 1) {
                    $source->resize(
                        max(1, (int) round($source->width() * $scale)),
                        max(1, (int) round($source->height() * $scale))
                    );
                }

                $preview = $manager
                    ->canvas($source->width(), $source->height(), '#ffffff')
                    ->insert($source)
                    ->encode('jpg', self::BANNER_QUALITY);

                if (! $cacheDisk->put($cachePath, (string) $preview)) {
                    return null;
                }
            }

            return 'data:image/jpeg;base64,'.base64_encode($cacheDisk->get($cachePath));
        } catch (Throwable) {
            return null;
        }
    }
}
