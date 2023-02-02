<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MonthPackage;
use Illuminate\Console\Command;

class PackageMonthNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:month:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package month notify';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $endPeriod = date('Y-m-d H:i:s', strtotime('+1 month'));
        $subDayPeriod = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($endPeriod)));

        $users = User::where('package_expired_at', '<=', $endPeriod)
            ->where('package_expired_at', '>', $subDayPeriod)
            ->get();

        foreach ($users as $user) {
            $user->notify(new MonthPackage($user->package->slug));
        }
    }
}
