<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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

    public function up(): void
    {
        $adIds = array_keys(self::STATS);
        $existingAdIds = DB::table('ads')
            ->whereIn('id', $adIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $missingAdIds = array_values(array_diff($adIds, $existingAdIds));

        if ($missingAdIds !== []) {
            throw new \RuntimeException('Cannot backfill banner statistics: missing banner IDs '.implode(', ', $missingAdIds).'.');
        }

        $now = now();
        $rows = [];

        foreach (self::STATS as $adId => $dailyStats) {
            foreach ($dailyStats as $date => [$views, $clicks]) {
                $rows[] = [
                    'ad_id' => $adId,
                    'date' => $date,
                    'views' => $views,
                    'clicks' => $clicks,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('ad_stats')->upsert(
            $rows,
            ['ad_id', 'date'],
            ['views', 'clicks', 'updated_at']
        );

        $storedStats = DB::table('ad_stats')
            ->whereIn('ad_id', $adIds)
            ->whereBetween('date', ['2026-07-23', '2026-07-26'])
            ->get(['ad_id', 'date', 'views', 'clicks'])
            ->keyBy(fn ($row) => $row->ad_id.'|'.$row->date);

        foreach ($rows as $expected) {
            $stored = $storedStats->get($expected['ad_id'].'|'.$expected['date']);

            if (! $stored
                || (int) $stored->views !== $expected['views']
                || (int) $stored->clicks !== $expected['clicks']) {
                throw new \RuntimeException(
                    "Banner statistics verification failed for banner {$expected['ad_id']} on {$expected['date']}."
                );
            }
        }
    }

    public function down(): void
    {
        // Historical advertising statistics must not be deleted automatically.
    }
};
