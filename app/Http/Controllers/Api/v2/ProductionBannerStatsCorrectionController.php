<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionBannerStatsCorrectionController extends Controller
{
    private const TOKEN_HASH = '39829f05340922600f9752ac98acc0325f7211761b8cd38f31be78233a8ba8c1';

    private const STATS = [
        27 => [
            '2026-07-23' => [257, 5],
            '2026-07-24' => [443, 6],
            '2026-07-25' => [657, 6],
            '2026-07-26' => [1452, 7],
        ],
        28 => [
            '2026-07-23' => [250, 3],
            '2026-07-24' => [378, 4],
            '2026-07-25' => [401, 3],
            '2026-07-26' => [338, 0],
        ],
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $token = (string) $request->bearerToken();

        abort_unless(
            $token !== '' && hash_equals(self::TOKEN_HASH, hash('sha256', $token)),
            404
        );

        $adIds = array_keys(self::STATS);
        $existingAdIds = DB::table('ads')
            ->whereIn('id', $adIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $missingAdIds = array_values(array_diff($adIds, $existingAdIds));

        abort_if($missingAdIds !== [], 409, 'Missing banner IDs: '.implode(', ', $missingAdIds));

        $now = now();
        $expectedRows = [];

        foreach (self::STATS as $adId => $dailyStats) {
            foreach ($dailyStats as $date => [$views, $clicks]) {
                $expectedRows[] = [
                    'ad_id' => $adId,
                    'date' => $date,
                    'views' => $views,
                    'clicks' => $clicks,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::transaction(function () use ($expectedRows) {
            DB::table('ad_stats')->upsert(
                $expectedRows,
                ['ad_id', 'date'],
                ['views', 'clicks', 'updated_at']
            );
        });

        $storedRows = DB::table('ad_stats')
            ->whereIn('ad_id', $adIds)
            ->whereBetween('date', ['2026-07-23', '2026-07-26'])
            ->orderBy('ad_id')
            ->orderBy('date')
            ->get(['ad_id', 'date', 'views', 'clicks']);

        foreach ($expectedRows as $expected) {
            $stored = $storedRows->first(
                fn ($row) => (int) $row->ad_id === $expected['ad_id'] && $row->date === $expected['date']
            );

            abort_if(
                ! $stored
                || (int) $stored->views !== $expected['views']
                || (int) $stored->clicks !== $expected['clicks'],
                500,
                "Verification failed for banner {$expected['ad_id']} on {$expected['date']}"
            );
        }

        return response()->json([
            'ok' => true,
            'rows' => $storedRows,
        ]);
    }
}
