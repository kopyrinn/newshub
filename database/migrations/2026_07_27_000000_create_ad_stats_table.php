<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_stats', function (Blueprint $table) {
            $table->id();
            // id баннера храним просто числом, без внешнего ключа —
            // таблица полностью автономна и никак не затрагивает другие таблицы.
            $table->unsignedInteger('ad_id')->index();
            $table->date('date');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamps();

            $table->unique(['ad_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_stats');
    }
};
