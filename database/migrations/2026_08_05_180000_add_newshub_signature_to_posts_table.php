<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('posts', 'newshub_signature')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->text('newshub_signature')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'newshub_signature')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('newshub_signature');
        });
    }
};
