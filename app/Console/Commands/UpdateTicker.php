<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateTicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ticker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ticker';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rates = [];

        try {
            $url = "https://nationalbank.kz/rss/rates_all.xml";
            $dataObj = simplexml_load_file($url);

            if (!$dataObj) return;

            foreach ($dataObj->channel->item as $item) {
                if (!in_array($item->title, ['USD', 'EUR', 'RUB', 'CNY',])) continue;

                $rates[(string) $item->title] = [
                    'price' => (float) $item->description,
                    'change' => (float) $item->change,
                ];
            }
        } catch(\Exception $e) {}

        Cache::put('tickers', $rates, 86440);

        return Command::SUCCESS;
    }
}
