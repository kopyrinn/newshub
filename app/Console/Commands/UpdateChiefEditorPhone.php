<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class UpdateChiefEditorPhone extends Command
{
    private const OLD_PHONE = '+77076501172';

    private const NEW_PHONE = '+77081683792';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:update-chief-editor-phone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the chief editor phone number on the contact page';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! app()->environment('production')) {
            $this->info('Chief editor phone update skipped outside production.');

            return self::SUCCESS;
        }

        try {
            $updatedLocales = DB::transaction(function (): int {
                $page = Page::where('slug', 'contact')
                    ->lockForUpdate()
                    ->first();

                if (! $page) {
                    throw new RuntimeException('The contact page was not found.');
                }

                $translations = $page->getTranslations('page_content');
                $updatedLocales = 0;

                foreach ($translations as $locale => $content) {
                    if (! str_contains($content, self::OLD_PHONE)) {
                        continue;
                    }

                    $updatedContent = str_replace(
                        self::OLD_PHONE,
                        self::NEW_PHONE,
                        $content,
                        $phoneReplacements
                    );

                    $updatedContent = preg_replace_callback(
                        '~<a\b(?P<attributes>[^>]*)>(?P<label>\s*'.preg_quote(self::NEW_PHONE, '~').'\s*)</a>~iu',
                        function (array $matches): string {
                            $attributes = preg_replace(
                                '~\bhref=(["\'])tel:[^"\']*\1~iu',
                                'href="tel:'.self::NEW_PHONE.'"',
                                $matches['attributes'],
                                1,
                                $hrefReplacements
                            );

                            if ($attributes === null || $hrefReplacements !== 1) {
                                throw new RuntimeException('The chief editor phone link could not be updated.');
                            }

                            return '<a'.$attributes.'>'.$matches['label'].'</a>';
                        },
                        $updatedContent,
                        -1,
                        $linkReplacements
                    );

                    if ($updatedContent === null || $phoneReplacements < 1 || $linkReplacements < 1) {
                        throw new RuntimeException("The chief editor phone markup for locale {$locale} is invalid.");
                    }

                    $translations[$locale] = $updatedContent;
                    $updatedLocales++;
                }

                if ($updatedLocales === 0) {
                    throw new RuntimeException('The old chief editor phone number was not found.');
                }

                $page->setTranslations('page_content', $translations);
                $page->save();

                return $updatedLocales;
            });
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Chief editor phone updated in {$updatedLocales} locale(s).");

        return self::SUCCESS;
    }
}
