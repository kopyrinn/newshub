<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'ads')->first();

        if (! $page) {
            return;
        }

        $content = json_decode($page->page_content, true, 512, JSON_THROW_ON_ERROR);
        $changed = false;

        foreach ($content as $locale => $localizedContent) {
            if (! is_string($localizedContent) || ! str_contains($localizedContent, 'adv.newshub.kz')) {
                continue;
            }

            $cleanedContent = preg_replace(
                '~<div\b[^>]*>(?:(?!</div>).)*adv\.newshub\.kz(?:(?!</div>).)*</div>\s*~isu',
                '',
                $localizedContent
            );

            if ($cleanedContent !== null && $cleanedContent !== $localizedContent) {
                $content[$locale] = $cleanedContent;
                $changed = true;
            }
        }

        if (! $changed) {
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
        // The original block can be restored by the preceding reversible migration.
    }
};
