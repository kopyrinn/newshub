<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $backfill = require database_path(
            'migrations/2026_07_31_120000_backfill_ad_stats_for_banners_27_and_28.php'
        );

        $backfill->up();
    }

    public function down(): void
    {
        // Historical advertising statistics must not be deleted automatically.
    }
};
