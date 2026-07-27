<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\AdStat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class AdReportController extends Controller
{
    /**
     * Человекочитаемые названия позиций баннера (совпадают с админкой).
     */
    private const LOCATIONS = [
        'header'        => 'БГВ-1',
        'post.view'     => 'В новости',
        'category.view' => 'В категории новости',
        'sidebar.view'  => 'БГП-1',
        'sidebar2.view' => 'БГП-2',
        'sidebar_alt'   => 'БГП-3',
    ];

    /**
     * PDF-отчёт по одному баннеру за один день.
     */
    public function day(Ad $ad, string $date)
    {
        $carbon = Carbon::parse($date);

        $stat = AdStat::where('ad_id', $ad->id)
            ->whereDate('date', $carbon->toDateString())
            ->first();

        $views  = $stat->views ?? 0;
        $clicks = $stat->clicks ?? 0;

        $pdf = Pdf::loadView('reports.ad-day', [
            'ad'            => $ad,
            'locationLabel' => $this->locationLabel($ad->location),
            'date'          => $carbon,
            'views'         => $views,
            'clicks'        => $clicks,
            'ctr'           => $views > 0 ? round($clicks / $views * 100, 2) : 0,
            'generatedAt'   => Carbon::now(),
        ]);

        return $pdf->download("banner-{$ad->id}-{$carbon->format('Y-m-d')}.pdf");
    }

    private function locationLabel(?string $location): string
    {
        if ($location && str_starts_with($location, 'home.')) {
            return 'БГЛ: ' . substr($location, 5);
        }

        return self::LOCATIONS[$location] ?? ($location ?? '—');
    }
}
