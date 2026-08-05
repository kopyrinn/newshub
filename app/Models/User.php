<?php

namespace App\Models;

use App\Traits\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasRoles;
use Illuminate\Http\Request;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'password',
        'city_id',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'public_token',
        'remember_token',
        'email_verify_token',
        'is_auto_moderate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'package_expired_at' => 'datetime',
        'newsletter' => 'boolean',
        'is_auto_moderate' => 'boolean',
    ];

    protected function avatarSm(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $value?: $attributes['avatar'],
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function userCategory()
    {
        return $this->belongsTo(UserCategory::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
            ->using(Follower::class);
    }

    public function feeds()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')
            ->using(Follower::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Post::class, 'post_favorite', 'user_id', 'post_id')
            ->using(PostFavorite::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function pollVotes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function drafts()
    {
        return $this->hasMany(Draft::class);
    }

    public function getName()
    {
        if ($this->isUser()) {
            return implode(" ", [$this->name, $this->lastname])?: substr($this->email, 0, strrpos($this->email, '@'));
        }

        return $this->name?: substr($this->email, 0, strrpos($this->email, '@'));
    }

    public function packageActive()
    {
        $status = $this->package_id && $this->package_expired_at && $this->package_expired_at->getTimestamp() > time();

        if (
            !$status &&
            (
                $this->package_press ||
                $this->package_events ||
                $this->package_vacancies ||
                $this->package_help ||
                $this->package_translate ||
                $this->package_pr
            )
        ) {
            $this->package_press = 0;
            $this->package_events = 0;
            $this->package_vacancies = 0;
            $this->package_help = 0;
            $this->package_translate = 0;
            $this->package_pr = 0;
            $this->update();
        }

        return $status;
    }

    public function isAdmin()
    {
        return $this->roles()->where('slug', 'admin')->exists();
    }

    public function isModerator()
    {
        return $this->roles()->where('slug', 'moderator')->exists();
    }

    public function isPress()
    {
        return $this->roles()->where('slug', 'press')->exists();
    }

    public function isUser()
    {
        return $this->roles()->where('slug', 'journalist')->exists();
    }

    public function subBalance($cost, $reason = "")
    {
        $balance = new Balance;
        $balance->cost = $cost;
        $balance->previous = $this->balance;
        $balance->user_id = $this->id;
        $balance->reason = $reason;
        $balance->save();

        $this->balance -= $cost;
    }

    public function getRecommendations()
    {
        $recommendations = self::select('users.*')
            ->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'roles.id', 'role_user.role_id')
            ->where('roles.slug', 'press')
            ->where('users.id', '!=', $this->id)
            ->whereNotNull('users.name')
            ->whereNotNull('users.email_verified_at')
            ->whereNotExists(function($query) {
                    $query->select(\DB::raw(1))
                        ->from('followers')
                        ->whereColumn('followers.user_id', 'users.id')
                        ->where('followers.follower_id', $this->id);
                })
            ->inRandomOrder()
            ->take(3)
            ->get();

        if ($recommendations->count() < 3) {
            $recommendationsFollowed = self::select('users.*')
                ->join('role_user', 'role_user.user_id', 'users.id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->where('roles.slug', 'press')
                ->where('users.id', '!=', $this->id)
                ->whereNotNull('users.name')
                ->whereNotNull('users.email_verified_at')
                ->whereExists(function($query) {
                        $query->select(\DB::raw(1))
                            ->from('followers')
                            ->whereColumn('followers.user_id', 'users.id')
                            ->where('followers.follower_id', $this->id);
                    })
                ->inRandomOrder()
                ->take(3 - $recommendations->count())
                ->get();

            foreach ($recommendationsFollowed as $recommendationFollowed) {
                $recommendations->add($recommendationFollowed);
            }
        }

        return $recommendations;
    }

    public function addAction($type, $content = [])
    {
        if (!$this->isPress()) {
            return;
        }

        $action = new Action;
        $action->type = $type;
        $action->content = $content;
        $this->actions()->save($action);
    }

    public function withInfo()
    {
        $this->is_package_active = $this->packageActive();
        $this->is_journalist = $this->isUser();
        $this->is_admin = $this->isAdmin();
        $this->notifications_count = $this->unreadNotifications()
            ->where('created_at', '<=', Carbon::now())
            ->count();

        $package = $this->package()->select('name', 'slug')->first();
        $this->package_name = $package? $package->name: '';
        $this->package_slug = $package? $package->slug: '';

        $this->allowed_categories = Category::select('categories.id', 'categories.name')
            ->join('category_role', 'category_role.category_id', '=', 'categories.id')
            ->whereIn('category_role.role_id', $this->roles()->pluck('id'))
            ->groupBy('categories.id')
            ->pluck('name', 'id');

        return $this;
    }

    public function createAppToken(Request $request)
    {
        $uuid = \Str::uuid()->toString();

        $accessToken = $this->createToken($uuid);

        $token = $this->tokens()->whereName($uuid)->first();
        $token->ip = $request->ip();
        $token->ua = $request->userAgent();
        $token->app_token = $request->appToken?: null;
        $token->platform = $request->platform?: 'web';
        $token->update();

        // \Log::info('token', $token->toArray());

        return $accessToken;
    }

    public function sendPasswordResetNotification($token)
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('app.origin') . "/password/reset/{$token}?email=" . $user->email;
        });

        $this->notify(new ResetPassword($token));
    }

    // public function routeNotificationForDatabase($notification)
    // {
    //     // dd($notification);
    //     $notification->targetable()->associate($notification->post);

    //     return $notification;
    // }
}
