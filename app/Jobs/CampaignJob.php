<?php

namespace App\Jobs;

use App\Mail\CampaignEmail;
use App\Models\Campaign;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class CampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->campaign->is_active) {
            return;
        }

        $packages = array_keys(array_filter($this->campaign->packages));
        $roles = array_keys(array_filter($this->campaign->roles));

        $this->campaign->update([
            'start_at' => Carbon::now()
        ]);

        $query = User::select('users.id', 'users.email', 'users.public_token');
    
        $query->where('users.newsletter', 1);
    
        if ($packages && count($packages) < 4) {
            $query->where(function($query) use ($packages) {
                if (!in_array('nopackage', $packages)) {
                    $query->whereIn('users.package_id', $packages);
                } else {
                    $query->whereNull('users.package_id');
                    $query->orWhereIn('users.package_id', array_filter($packages, fn($val) => is_int($val)));
                }
            });
        }
    
        $query->whereExists(function($query) use ($roles) {
            $query->selectRaw(1)
                ->from('role_user')
                ->whereColumn('role_user.user_id', 'users.id')
                ->whereIn('role_user.role_id', $roles);
        });
    
        if ($this->campaign->has_activity && ($this->campaign->activity_gte || $this->campaign->activity_lte)) {
            $query->whereExists(function($query) {
                $query->selectRaw(1)
                    ->from('personal_access_tokens')
                    ->where('personal_access_tokens.tokenable_type', User::class)
                    ->whereColumn('personal_access_tokens.tokenable_id', 'users.id');
    
                if ($this->campaign->activity_gte) {
                    $query->where('personal_access_tokens.last_used_at', '<=', Carbon::now()->sub($this->campaign->activity_gte));
                }
    
                if ($this->campaign->activity_lte) {
                    $query->where('personal_access_tokens.last_used_at', '>=', Carbon::now()->sub($this->campaign->activity_lte));
                }
            });
        }

        if ($this->campaign->has_regions) {
            $regions = array_keys(array_filter($this->campaign->regions));
            $cityIds = City::whereIn('region_id', array_filter($regions, fn($val) => is_int($val)))->pluck('id');
            if ($cityIds) {
                $query->whereIn('city_id', $cityIds);
            }
        }

        $users = $query->get();

        $this->campaign->update([
            'total' => $users->count()
        ]);

        if ($users->count()) {
            foreach ($users as $i => $user) {
                App::setLocale('ru');

                $limitPerMinute = 30;

                Mail::to($user)->later(
                    now()->addMinutes((int) ceil(($i + 1) / $limitPerMinute)),
                    new CampaignEmail($this->campaign, $user->public_token)
                );

                $this->campaign->update([
                    'sent' => $users->count()
                ]);
            }
        }

        $this->campaign->update([
            'ends_at' => Carbon::now()
        ]);
    }
}
