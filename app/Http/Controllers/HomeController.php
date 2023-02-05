<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\UserCategory;
use App\Models\Category;
use App\Models\GrammaticalError;
use App\Models\Page;
use App\Models\Post;
use App\Models\Region;
use App\Models\Rubric;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SchemaOrg\Schema;
use App\Helpers\Util;
use App\Models\Poll;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function index(Request $request)
    {

        
        
        $breaking = Post::where('is_breaking', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $slider = Post::where('is_slider', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $featured = Post::where('is_featured', 1)
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->limit(4)
            ->get();

        $widgets = Widget::orderBy('position')
            ->get();
        // ~r($widgets);

        return view('home', [
            'breaking' => $breaking,
            'slider' => $slider,
            'featured' => $featured,
            'widgets' => $widgets,
        ]);
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token' => $request->token]);
        
        return response()->json(['token saved successfully.']);
    }

    public function sendNotification(Request $request)
    {
//        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
//
//        $SERVER_API_KEY = 'XXXXXX';
//
//        $data = [
//            "registration_ids" => $firebaseToken,
//            "notification" => [
//                "title" => $request->title,
//                "body" => $request->body,
//            ]
//        ];
//        $dataString = json_encode($data);
//
//        $headers = [
//            'Authorization: key=' . $SERVER_API_KEY,
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
//        $response = curl_exec($ch);
//
//        dd($response);
    }
    
    public function polls(Request $request)
    {
        $polls = Poll::where('is_active', 1)
            ->where('expired_at', '>', Carbon::now())
            ->paginate(6);

        return view('polls', [
            'polls' => $polls
        ]);
    }

    public function poll(Request $request, $slug)
    {
        $poll = Poll::whereSlug($slug)->first();
        abort_if(!$poll, 404);

        $poll->participants = $poll->requests()
            ->select('poll_requests.*')
            ->selectRaw('(SELECT COUNT(*) FROM poll_votes WHERE poll_votes.poll_request_id = poll_requests.id AND poll_votes.poll_id = poll_requests.poll_id) as votes_count')
            ->where('status', 'done')
            ->get();

        $poll->total_votes = $poll->participants->count()? $poll->participants->sum('votes_count'): 0;

        return view('poll', [
            'poll' => $poll
        ]);
    }

    public function packages(Request $request)
    {
        if (!auth()->guest()) {
            $notifications = auth()->user()->unreadNotifications()->get();
            foreach ($notifications as $notification) {
                if (!empty($notification->data['package']) && $notification->data['package']) {
                    $notification->markAsRead();
                    break;
                }
            }
        }

        return view('packages');
    }

    public function users(Request $request)
    {
        return view('users');
    }

    public function map(Request $request)
    {
        $regions = Region::all();
        $usersCount = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->whereIn('roles.slug', ['journalist'])
            ->whereNotNull('users.email_verified_at')
            ->count();

        return view('map', [
            'regions' => $regions,
            'usersCount' => $usersCount,
        ]);
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
            ->paginate(15)
            ->withQueryString();

        return view('journalists', [
            'users' => $users,
        ]);
    }

    public function usersCategory(Request $request, $slug)
    {
        $userCategory = UserCategory::where('slug', $slug)
            ->first();

        abort_if(!$userCategory, 404);

        $users = $userCategory->users()
            ->select('users.*')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.slug', 'press')
            ->latest('users.created_at')
            ->get();

        return view('users_category', [
            'userCategory' => $userCategory,
            'users' => $users,
        ]);
    }

    public function user(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $posts = $user->posts()
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->groupBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('user', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function vacancies(Request $request)
    {
        $vacancies = Vacancy::where('status', 1)
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('vacancies', [
            'vacancies' => $vacancies,
        ]);
    }

    public function vacancy(Request $request, $id)
    {
        $vacancy = Vacancy::where('status', 1)
            ->where('id', $id)
            ->first();

        abort_if(!$vacancy, 404);

        $vacancy->vacancy_view++;
        $vacancy->update();

        return view('vacancy', [
            'vacancy' => $vacancy,
        ]);
    }

    public function search(Request $request)
    {
        $query = Post::where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now());

        if ($request->q) {
            $token = $request->q;
            $query->where(function($query) use ($token) {
                $query->where('title', 'like', "%{$token}%")
                    ->orWhere('content', 'like', "%{$token}%");
            });
        }

        $posts = $query->latest('created_at')
            ->groupBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('search', [
            'posts' => $posts,
        ]);
    }

    public function category(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)
            ->first();

        abort_if(!$category, 404);

        $posts = $category->posts()
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->groupBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function tag(Request $request, $tag)
    {
        $query = Post::where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now());

        $query->where('keywords', 'like', "%{$tag}%");

        $posts = $query->latest('created_at')
            ->groupBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('tags', [
            'tag' => $tag,
            'posts' => $posts,
        ]);
    }

    public function categoryRubric(Request $request, $slug, $rubricSlug)
    {
        $category = Category::where('slug', $slug)
            ->first();
        abort_if(!$category, 404);

        $rubric = Rubric::where('slug', $rubricSlug)->first();
        abort_if(!$rubric, 404);

        $posts = $category->posts()
            ->join("post_rubric", "posts.id", "=", "post_rubric.post_id")
            ->join("rubrics", "rubrics.id", "=", "post_rubric.rubric_id")
            ->where("rubrics.slug", $rubricSlug)
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', \Carbon\Carbon::now())
            ->latest('posts.created_at')
            ->groupBy('posts.id')
            ->paginate(10)
            ->withQueryString();

        return view('category_rubric', [
            'category' => $category,
            'rubric' => $rubric,
            'posts' => $posts,
        ]);
    }

    public function page(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)
            ->first();

        abort_if(!$page, 404);

        return view('page', [
            'page' => $page
        ]);
    }

    public function postGrammar(Request $request)
    {
        $post = Post::where('slug', $request->slug)
            ->first();

        abort_if(!$post, 404);

        $grammar = new GrammaticalError;
        $grammar->post_id = $post->id;

        if (!auth()->guest()) {
            $grammar->user_id = auth()->user()->id;
        }
        $grammar->error = $request->error;
        $grammar->suggestion = $request->suggestion;
        $grammar->save();

        return response()->json(['ok' => true]);
    }

    public function post(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

        // r(\LaravelLocalization::getLocaleFromMapping('kk'));
        // r($post->getTranslations('title'));
        // ~r($post);

        $schema = Schema::newsArticle()
            ->image(asset("storage/" . $post->image))
            ->headline($post->title)
            ->description($post->getSummary(100))
            ->articleBody($post->getSummary(200))
            ->datePublished($post->created_at->toAtomString())
            ->dateModified($post->updated_at->toAtomString())
            ->author([
                '@type' => 'Person',
                'name' => [
                    $post->user->getName()
                ]
            ])
            ->publisher([
                '@type' => 'Organization',
                'name' => 'newshub.kz',
                'telephone' => '+77772555856',
                'email' => 'info@newshub.kz',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'мкрн. "Жетысу-2", дом 59',
                    'postalCode' => '050063',
                    'addressLocality' => 'Алматы',
                ],
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                    'width' => 302,
                    'height' => 61,
                ],
            ])
            ->mainEntityOfPage([
                '@type' => 'WebPage',
                '@id' => url("post/{$post->slug}"),
            ]);

        $post->pageviews++;
        $post->update();

        if (!auth()->guest()) {
            $notifications = auth()->user()->unreadNotifications()->get();
            foreach ($notifications as $notification) {
                if (!empty($notification->data['post_id']) && $notification->data['post_id'] == $post->id) {
                    $notification->markAsRead();
                    break;
                }
            }
        }

        return view('post', [
            'post' => $post,
            'schema' => $schema,
        ]);
    }

    public function postAmp(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

        // r(\LaravelLocalization::getLocaleFromMapping('kk'));
        // r($post->getTranslations('title'));
        // ~r($post);

        $schema = Schema::newsArticle()
            ->image(asset("storage/" . $post->image))
            ->headline($post->title)
            ->description($post->getSummary(100))
            ->articleBody($post->getSummary(200))
            ->datePublished($post->created_at->toAtomString())
            ->dateModified($post->updated_at->toAtomString())
            ->author([
                '@type' => 'Person',
                'name' => [
                    $post->user->getName()
                ]
            ])
            ->publisher([
                '@type' => 'Organization',
                'name' => 'newshub.kz',
                'telephone' => '+77772555856',
                'email' => 'info@newshub.kz',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'мкрн. "Жетысу-2", дом 59',
                    'postalCode' => '050063',
                    'addressLocality' => 'Алматы',
                ],
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                    'width' => 302,
                    'height' => 61,
                ],
            ])
            ->mainEntityOfPage([
                '@type' => 'WebPage',
                '@id' => url("post/{$post->slug}"),
            ]);

        $post->pageviews++;
        $post->update();

        if (!auth()->guest()) {
            $notifications = auth()->user()->unreadNotifications()->get();
            foreach ($notifications as $notification) {
                if (!empty($notification->data['post_id']) && $notification->data['post_id'] == $post->id) {
                    $notification->markAsRead();
                    break;
                }
            }
        }

        return view('post-amp', [
            'post' => $post,
            'schema' => $schema,
        ]);
    }

    public function morePosts(Request $request, $offset)
    {
        $category = Category::where('slug', 'news')->first();

        $posts = $category->posts()
            ->where('status', 1)
            ->where('created_at', '<', \Carbon\Carbon::now())
            ->latest('created_at')
            ->groupBy('id')
            ->skip($offset)
            ->take(10)
            ->get();

        return view('post-more', [
            'posts' => $posts,
        ]);
    }

    public function postPrev(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

        $prevPost = $post->previous();

        if ($prevPost) {
            $prevPost->pageviews++;
            $prevPost->update();

            return response()->json([
                'status' => true,
                'markup' => view('post-prev', [
                    'post' => $prevPost
                ])->render(),
            ]);
        }

        return response()->json([
            'status' => false,
            'markup' => '',
        ]);
    }

    public function postTranslate(Request $request, $locale, $slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

        $post->setLocale($locale);

        return response()->json([
            'status' => true,
            'markup' => view('post-prev', [
                'post' => $post
            ])->render(),
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

    public function file(Request $request, $slug, $name)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

        $files = $post->getFiles();
        foreach ($files as $file) {
            $filename = basename($file->name);
            if (md5($filename) == $name) {
                $path = ltrim($file->url, '/');
                $path = strtr($path, [
                    'https://newshub.kz/storage/' => '',
                    'http://newshub.kz/storage/' => '',
                    '/storage/' => '',
                    'storage/' => '',
                ]);
                // dd($path);

                $post->files_downloaded++;
                $post->update();

                return response()->download(\Storage::disk('public')->path($path), $file->originalName);
            }
        }

        return abort(404);
    }

    public function getMoneyList(Request $request){
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
}
