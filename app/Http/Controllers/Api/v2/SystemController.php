<?php

namespace App\Http\Controllers\Api\v2;

use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Package;
use App\Models\Page;
use App\Models\Post;
use App\Models\Region;
use App\Models\User;
use App\Models\UserCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
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
        $regions = Region::all();

        $query = User::select('users.*')
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

        $users = $query->groupBy('users.id')
            ->cursorPaginate(15);

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

    public function packages(Request $request)
    {
        if (!auth('sanctum')->guest()) {
            $notifications = auth('sanctum')->user()->unreadNotifications()->get();
            foreach ($notifications as $notification) {
                if (!empty($notification->data['package']) && $notification->data['package']) {
                    $notification->markAsRead();
                    break;
                }
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

        $notifications = $user->unreadNotifications()->get();
        foreach ($notifications as $notification) {
            if (!empty($notification->data['package'])) {
                $notification->markAsRead();
                break;
            }
        }

        if ($request->method() == 'POST') {
            if (!in_array($request->period, [1, 3, 6, 12])) {
                return response()->json([
                    'ok' => false,
                    'message' => __("The selected period does not exist"),
                ]);
            }

            $price = $package->{"price_{$request->period}"};

            if ($user->balance < $price) {
                return response()->json([
                    'ok' => false,
                    'message' => __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."),
                ]);
            }

            $user->package_id = $package->id;

            if ($slug == 'standart') {
                $user->package_press = 15;
                $user->package_events = 0;
                $user->package_vacancies = 0;
                $user->package_help = 0;
                $user->package_translate = 0;
                $user->package_pr = 0;
            } else if ($slug == 'standart-plus') {
                $user->package_press = 25;
                $user->package_events = 15;
                $user->package_vacancies = 10;
                $user->package_help = 2;
                $user->package_translate = 0;
                $user->package_pr = 0;
            } else if ($slug == 'standart-maximum') {
                $user->package_press = 50;
                $user->package_events = 25;
                $user->package_vacancies = 25;
                $user->package_help = 4;
                $user->package_translate = 4;
                $user->package_pr = 2;
            }

            $user->package_expired_at = date('Y-m-d H:i:s', strtotime("+{$request->period} month"));
            $user->subBalance($price, "Оплата тарифа {$package->name} на {$request->period} мес.");
            $user->update();

            return response()->json([
                'ok' => true,
                'message' => __("Your package has been successfully activated")
            ]);
            // return redirect('profile')->with('success', );
        }

        return response()->json([
            'ok' => true,
            'package' => $package
        ]);
    }
}
