<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OBSOLETE_LINK_BLOCK = <<<'HTML'
<div>Подробная информация смотрите на <a href="https://adv.newshub.kz">https://adv.newshub.kz</a></div>
HTML;

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'ads')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->page_content, true, 512, JSON_THROW_ON_ERROR);
        $russianContent = $content['ru'] ?? '';
        $content['ru'] = str_replace(
            [self::OBSOLETE_LINK_BLOCK."\r\n", self::OBSOLETE_LINK_BLOCK."\n", self::OBSOLETE_LINK_BLOCK],
            '',
            $russianContent
        );

        if ($content['ru'] === $russianContent) {
            return;
        }

        DB::table('pages')
            ->where('id', $page->id)
            ->update([
                'page_content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        $page = DB::table('pages')->where('slug', 'ads')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->page_content, true, 512, JSON_THROW_ON_ERROR);
        $russianContent = $content['ru'] ?? '';

        if (str_contains($russianContent, 'adv.newshub.kz')) {
            return;
        }

        $heading = '<div>&nbsp;<strong>Реклама</strong></div>';
        $content['ru'] = str_replace(
            $heading,
            $heading."\r\n".self::OBSOLETE_LINK_BLOCK,
            $russianContent
        );

        DB::table('pages')
            ->where('id', $page->id)
            ->update([
                'page_content' => json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'updated_at' => now(),
            ]);
    }
};
