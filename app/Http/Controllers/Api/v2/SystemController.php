<?php

namespace App\Http\Controllers\Api\v2;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Package;
use App\Models\Page;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Region;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    private const PACKAGE_LEVELS = [
        'standart' => 1,
        'standart-plus' => 2,
        'standart-maximum' => 3,
    ];

    public function feed(Request $request)
    {
        $posts = Post::query()
            ->where('created_at', '<=', now())
            ->where('created_at', '>=', now()->subWeeks(1))
            ->where('status', 1)
            ->with('user')
            ->latest('created_at')
            ->take(500)
            ->get();

        return response()->view('rss.feed', [
            'posts' => $posts,
        ])->header('Content-Type', 'application/xml');
    }

    public function map(Request $request)
    {
        $regions = Region::all()->map(function($item) {
            $item->journalists_count = $item->getUsersCount(['journalist']);
            return $item;
        });

        $usersCount = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereIn('roles.slug', ['journalist'])
            ->whereNotNull('users.email_verified_at')
            ->count();

        return response()->json([
            'ok' => true,
            'regions' => $regions,
            'usersCount' => $usersCount,
        ]);
    }

    public function config(Request $request)
    {
        $locale = in_array($request->header('locale'), ['en', 'kk', 'ru'])? $request->header('locale'): 'ru';
        return response()->json(Cache::get("hubconfig:{$locale}"), 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function journalists(Request $request)
    {
        $query = User::select(
                'users.id',
                'users.avatar',
                'users.avatar_sm',
                'users.name',
                'users.lastname',
                'users.media_name',
                'users.description',
                'users.created_at',
                'cities.city_name_ru as city_name',
                'regions.region_name_ru as region_name',
            )
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('cities', 'cities.id', '=', 'users.city_id')
            ->join('regions', 'regions.id', '=', 'cities.region_id')
            ->whereIn('roles.slug', ['journalist'])
            ->whereNotNull('users.email_verified_at');

        if ($request->region) {
            $query->where('regions.id', $request->region);
        }

        if ($request->city) {
            $query->where('users.city_id', $request->city);
        }

        if ($request->q) {
            $token = $request->q;
            $query->whereRaw("CONCAT_WS(' ', users.name, regions.region_name_ru, cities.city_name_ru) LIKE '%{$token}%'");
        }

        $users = $query->groupBy('users.id')->cursorPaginate(15);

        return response()->json([
            'ok' => true,
            'users' => $users,
        ]);
    }

    public function page(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)
            ->first();

        abort_if(!$page, 404);

        return response()->json([
            'ok' => true,
            'page' => $page
        ]);
    }

    public function goto(Request $request, $id)
    {
        $ad = Ad::find($id);
        abort_if(!$ad, 404);

        $ad->clicks++;
        $ad->update();

        return redirect($ad->url);
    }

    public function getMoneyList(Request $request) {
        $money_rate = Util::getMoneyRates();
        //echo var_dump($money_rate);
        if(isset($money_rate[0]['description'])){
            $result['usd'] =  $money_rate[0]['description'];
            $result['euro'] = $money_rate[1]['description'];
            $result['rub'] =  $money_rate[2]['description'];
            $result['cny'] =  $money_rate[3]['description'];
            $result['status'] = true;
        }
        else $result['status'] = false;

        return response()->json($result);
    }

    public function search(Request $request)
    {
        $queryPost = Post::select('title', 'summary', 'slug', 'image', 'image_md', 'image_sm', 'image_blur')
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now());

        $queryPoll = Poll::select('question', 'slug', 'image', 'image_md', 'image_sm', 'image_blur')
            ->where('is_active', 1);

        $queryVacancy = Vacancy::select('id', 'job_title')
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now());

        if ($request->q) {
            $token = $request->q;

            $queryPost->where('title', 'like', "%{$token}%");
            $queryPoll->where('question', 'like', "%{$token}%");
            $queryVacancy->where('job_title', 'like', "%{$token}%");
        }

        $posts = $queryPost->latest('created_at')->take(5)->get();
        $polls = $queryPoll->latest('created_at')->take(5)->get();
        $vacancies = $queryVacancy->latest('created_at')->take(5)->get();

        return response()->json([
            'ok' => true,
            'posts' => $posts,
            'polls' => $polls,
            'vacancies' => $vacancies,
        ]);
    }

    public function searchV2(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);

        $query = Post::select(
                'posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.image_md', 'posts.image_sm', 'posts.image_blur', 'posts.pageviews', 'posts.summary', 'posts.created_at', 'posts.event_date', 'posts.article_type', 'users.name', 'users.avatar', 'users.avatar_sm',
            )
            // ->join('category_post', 'category_post.post_id', 'posts.id')
            ->join('users', 'users.id', 'posts.user_id')
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now());

        if ($request->q) {
            $token = $request->q;
            $query->where('title', 'like', "%{$token}%");
        }

        $posts = $query
            ->orderByDesc('posts.id')
            ->paginate(10);

        foreach ($posts as $post) {
            $post->categoriesSlugs = $post->categories()->select('slug')->groupBy('slug')->pluck('slug')->toArray(); 
        }

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function archive(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $query = Post::select(
                'posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.image_md', 'posts.image_sm', 'posts.image_blur', 'posts.pageviews', 'posts.summary', 'posts.created_at', 'posts.event_date', 'posts.article_type', 'users.name', 'users.avatar', 'users.avatar_sm',
            )
            // ->join('category_post', 'category_post.post_id', 'posts.id')
            ->join('users', 'users.id', 'posts.user_id')
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now());

        if ($request->date) {
            $date = now()->parse($request->date);

            $query->where('posts.created_at', '>=', $date->format('Y-m-d 00:00:00'));
            $query->where('posts.created_at', '<=', $date->format('Y-m-d 23:59:59'));
        }

        $posts = $query
            ->orderByDesc('posts.created_at')
            ->paginate(10);

        foreach ($posts as $post) {
            $post->categoriesSlugs = $post->categories()->select('slug')->groupBy('slug')->pluck('slug')->toArray(); 
        }

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function packages(Request $request)
    {
        if (!auth('sanctum')->guest()) {
            $user = auth('sanctum')->user();

            $notifications = $user
                ->unreadNotifications()
                ->select('id', 'read_at')
                ->whereTargetableType(Package::class)
                ->get();

            foreach ($notifications as $notification) {
                $notification->markAsRead();
            }
        }

        $packages = Package::select('id', 'name', 'slug', 'description', 'price_1', 'price_3', 'price_6', 'price_12')
            ->get()
            ->map(function($item) {
                $item->features = $item->packageFeatures()->select('feature')->pluck('feature');
                return $item;
            });

        return response()->json([
            'ok' => true,
            'packages' => $packages,
        ]);
    }

    public function packagesPay(Request $request, $slug)
    {
        $user = auth('sanctum')->user();

        if (!$user->isPress()) {
            return response()->json([
                'ok' => false,
                'message' => __("Service packages are available for purchase only for press centers"),
            ]);
        }

        $package = Package::where('slug', $slug)
            ->first();

        abort_if(!$package, 404);

        $notifications = $user
            ->unreadNotifications()
            ->select('id', 'read_at')
            ->whereTargetableType(Package::class)
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        if ($request->method() == 'POST') {
            if (!in_array($request->period, [1, 3, 6, 12])) {
                return response()->json([
                    'ok' => false,
                    'message' => __("The selected period does not exist"),
                ]);
            }

            return DB::transaction(function () use ($user, $package, $slug, $request) {
                $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                $period = (int) $request->period;
                $price = $package->{"price_{$period}"};

                $hasActivePackage = $user->packageActive();
                $currentPackage = $hasActivePackage
                    ? Package::select('id', 'slug')->find($user->package_id)
                    : null;
                $isExtension = $currentPackage && (int) $currentPackage->id === (int) $package->id;
                $isUpgrade = $currentPackage
                    && (self::PACKAGE_LEVELS[$slug] ?? 0) > (self::PACKAGE_LEVELS[$currentPackage->slug] ?? 0);

                if ($hasActivePackage && ! $isExtension && ! $isUpgrade) {
                    return response()->json([
                        'ok' => false,
                        'message' => __("You cannot downgrade an active package"),
                    ]);
                }

                if ($user->balance < $price) {
                    return response()->json([
                        'ok' => false,
                        'message' => __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."),
                    ]);
                }

                $limits = match ($slug) {
                    'standart' => [15, 0, 0, 0, 0, 0],
                    'standart-plus' => [25, 15, 10, 2, 0, 0],
                    'standart-maximum' => [50, 25, 25, 4, 4, 2],
                    default => [0, 0, 0, 0, 0, 0],
                };
                $fields = [
                    'package_press',
                    'package_events',
                    'package_vacancies',
                    'package_help',
                    'package_translate',
                    'package_pr',
                ];

                $user->package_id = $package->id;

                foreach ($fields as $index => $field) {
                    $remaining = ($isExtension || $isUpgrade) ? (int) $user->{$field} : 0;
                    $user->{$field} = $remaining + ($limits[$index] * $period);
                }

                $expiresFrom = ($isExtension || $isUpgrade)
                    ? $user->package_expired_at->copy()
                    : Carbon::now();

                $user->package_expired_at = $expiresFrom->addMonthsNoOverflow($period);
                $reason = match (true) {
                    $isUpgrade => 'Повышение',
                    $isExtension => 'Продление',
                    default => 'Оплата',
                };
                $user->subBalance($price, "{$reason} тарифа {$package->name} на {$period} мес.");
                $user->update();

                return response()->json([
                    'ok' => true,
                    'message' => __(match (true) {
                        $isUpgrade => "Your package has been successfully upgraded",
                        $isExtension => "Your package has been successfully extended",
                        default => "Your package has been successfully activated",
                    }),
                ]);
            });
            // return redirect('profile')->with('success', );
        }

        return response()->json([
            'ok' => true,
            'package' => $package
        ]);
    }

    public function unsubscribe(Request $request, $slug)
    {
        $user = User::select('id', 'newsletter')
            ->where('public_token', $slug)
            ->where('newsletter', 1)
            ->first();

        if ($user) {
            $user->newsletter = 0;
            $user->update();
        }

        return response([
            'ok' => true
        ]);
    }

    public function viewed(Request $request)
    {
        $ad = Ad::select('id', 'views')
            ->where('uuid', $request->uuid)
            ->first();

        if ($ad) {
            $ad->views += 1;
            $ad->update();

            $this->recordDailyStat($ad->id, 'views');
        }

        return response([
            'ok' => true
        ]);
    }

    public function clicked(Request $request)
    {
        $ad = Ad::select('id', 'clicks')
            ->where('uuid', $request->uuid)
            ->first();

        if ($ad) {
            $ad->clicks += 1;
            $ad->update();

            $this->recordDailyStat($ad->id, 'clicks');
        }

        return response([
            'ok' => true
        ]);
    }

    /**
     * Атомарно инкрементирует дневную статистику баннера (показы или клики).
     * Одна строка на пару баннер + дата; при повторе за тот же день значение растёт.
     */
    private function recordDailyStat(int $adId, string $field): void
    {
        if (!in_array($field, ['views', 'clicks'], true)) {
            return;
        }

        // Статистика вторична: любая ошибка записи не должна ронять показ/клик баннера.
        try {
            DB::table('ad_stats')->upsert(
                [[
                    'ad_id'      => $adId,
                    'date'       => now()->toDateString(),
                    'views'      => $field === 'views' ? 1 : 0,
                    'clicks'     => $field === 'clicks' ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]],
                ['ad_id', 'date'],
                [$field => DB::raw("{$field} + 1"), 'updated_at' => now()]
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
