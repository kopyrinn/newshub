<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Draft;
use App\Models\Package;
use App\Models\Poll;
use App\Models\PollRequest;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\Region;
use App\Models\User;
use App\Models\Vacancy;
use App\Notifications\AdminNotice;
use App\Notifications\ChannelNotification;
use App\Notifications\NewPollRequest;
use App\Notifications\NewPost;
use App\Notifications\NewPress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userFollow(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $me = auth()->user();
        if (!$me->feeds()->where('user_id', $user->id)->exists()) {
            $me->feeds()->attach($user->id);
        }

        return redirect()->back()->with('success', __("You have successfully subscribed to the press center news") . " {$user->name}");
    }

    public function userUnfollow(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $me = auth()->user();
        $me->feeds()->detach($user->id);

        return redirect()->back()->with('success', __("You have successfully unsubscribed from the press center news") . " {$user->name}");
    }

    public function feed(Request $request)
    {
        $user = auth()->user();

        $query = Post::query();
        $query->join('followers', 'followers.user_id', '=', 'posts.user_id');
        $query->where('followers.follower_id', $user->id);
        $query->where('posts.status', 1);
        $query->where('posts.created_at', '<', \Carbon\Carbon::now());

        $posts = $query
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('feed', [
            'posts' => $posts,
        ]);
    }

    public function packagesPay(Request $request, $slug)
    {
        $user = auth()->user();

        if (!$user->isPress()) {
            return redirect()->back()->with('warning', __("Service packages are available for purchase only for press centers"));
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
                return redirect()->back()->with('error', __("The selected period does not exist"));
            }

            $price = $package->{"price_{$request->period}"};

            if ($user->balance < $price) {
                return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
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

            return redirect('profile')->with('success', __("Your package has been successfully activated"));
        }

        return view('pay', [
            'package' => $package
        ]);
    }

    public function newVacancy(Request $request)
    {
        return view('new-vacancy');
    }

    public function newVacancySave(Request $request)
    {
        $request->validate([
            'job_title' => 'required',
            'requiremets' => 'required',
            'task' => 'required',
            'conditionsm' => 'required',
            'email_jobseeker' => 'required',
        ]);

        $user = auth()->user();

        $vacancy = new Vacancy;
        $vacancy->status = 0;
        $vacancy->user_id = $user->id;
        $vacancy->job_title = $request->job_title;
        $vacancy->requiremets = $request->requiremets;
        $vacancy->task = $request->task;
        $vacancy->conditionsm = $request->conditionsm;
        $vacancy->email_jobseeker = $request->email_jobseeker;

        if ($user->packageActive() && $user->package_vacancies) {
            $user->package_vacancies -= 1;
            $user->update();

            $vacancy->status = 1;

            $message = __("Your news has been successfully published");
        } else {
            if ($user->balance < nova_get_setting('vacancy_price')) {
                return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
            }

            $user->subBalance(nova_get_setting('vacancy_price'), "Оплата публикации вакансии");
            $user->update();

            $message = __("Your vacancy has been successfully submitted for moderation");
        }

        $vacancy->save();

        return redirect()->back()->with('success', $message);
    }

    public function new(Request $request, $step = 1, $id = 0)
    {
        $user = auth()->user();

        if ($id) {
            $draft = Draft::find($id);
        } else {
            $draft = new Draft;
        }

        $categories = Category::select('categories.*')
            ->join('category_role', 'category_role.category_id', '=', 'categories.id')
            ->whereIn('category_role.role_id', $user->roles()->pluck('id'))
            ->groupBy('categories.id')
            ->get();

        return view('new', [
            'id' => $id,
            'step' => $step,
            'draft' => $draft,
            'categories' => $categories,
        ]);
    }

    public function draftSave(Request $request)
    {
        $user = auth()->user();

        if ($request->id) {
            $draft = Draft::find($request->id);
        } else {
            $draft = new Draft;
        }

        if ($request->step == 2) {
            $request->validate([
                'category_id' => 'required',
                'title' => 'required',
                'image' => 'required',
            ]);

            $category = Category::find($request->category_id);

            if ($category->slug == 'sobitiya') {
                $request->validate([
                    'event_date' => 'required',
                ]);
            }
        } else if ($request->step == 3) {
            $request->validate([
                'content' => 'required',
            ]);
        }

        $content = json_decode($draft->content, true)?: [];
        $content = array_merge($content, $request->except(['id', '_token', 'step']));
        $draft->content = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $user->drafts()->save($draft);

        // ~r($content);
        if ($request->step == 4) {
            $post = new Post;
            $post->status = 0;
            $post->image = ltrim(str_replace(url('storage'), '', Arr::get($content, 'image')), '/');
            $post->image_caption = Arr::get($content, 'image_caption');
            $post->user_id = $user->id;
            $post->author_id = $user->id;
            $post->event_date = Arr::get($content, 'event_date');
            $post->created_at = Arr::get($content, 'created_at')?: date('Y-m-d H:i:s');
            $post->is_breaking = 0;

            $post->setTranslation('title', Arr::get($content, 'locale'), Arr::get($content, 'title'));
            $post->slug = \Str::slug(Arr::get($content, 'title'), '-') . '-'. time();

            $post->setTranslation('summary', Arr::get($content, 'locale'), Arr::get($content, 'summary'));

            foreach (Arr::get($content, 'content') as $locale => $value) {
                $post->setTranslation('content', $locale, clean($value));
            }

            if ($user->isModerator() || $user->isAdmin() || ($user->packageActive() && in_array($user->package->slug, ['standart-plus', 'standart-maximum']))) {
                $post->to_fcm = Arr::get($content, 'to_fcm')? 1: 0;
                $post->to_telegram = Arr::get($content, 'to_telegram')? 1: 0;
            }

            if (Arr::get($content, 'files', [])) {
                $files = [];

                foreach (Arr::get($content, 'files', []) as $file) {
                    $file = '/' . ltrim(str_replace(url('/'), '', $file), '/');
                    $files[] = [
                        "url" => $file,
                        "name" => basename($file),
                        "originalName" => basename($file)
                    ];
                }

                $post->files = json_encode($files, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }

            // if (Arr::get($content, 'is_styled', 0) && $user->packageActive() && $user->package_styles) {
            //     $user->package_styles -= 1;
            //     $user->update();

            //     $post->is_styled = 1;
            //     $post->style_color = Arr::get($content, 'color');
            // }

            $category = Category::find(Arr::get($content, 'category_id'));

            if ($user->isModerator() || $user->isAdmin()) {
                $post->status = 1;
                $post->save();

                $message = __("Your news has been published");
            } else if ($user->packageActive() && $user->package_press && $category->slug == 'press-release-3') {
                $user->package_press -= 1;
                $user->update();

                $post->save();

                $message = __("Your news has been sent for moderation");
            } else if ($user->packageActive() && $user->package_events && $category->slug == 'sobitiya') {
                $user->package_events -= 1;
                $user->update();

                $post->save();

                $message = __("Your news has been sent for moderation");
            } else if ($user->packageActive() && $category->slug == 'intervyu') {
                $post->save();

                $message = __("Your news has been sent for moderation");
            } else {
                return redirect()->back()->with('error', __("To publish, you need to purchase a subscription"));
            }

            $is_styled = Arr::get($content, 'is_styled', 0);
            if ($is_styled) {
                $price = nova_get_setting("style_card_price");
                if ($user->balance < $price) {
                    return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
                }

                $user->subBalance($price, "Оплата цветной карточки поста");
                $user->update();

                $post->is_styled = 1;
                $post->style_color = Arr::get($content, 'color');

                $post->update();
            }

            $add_to_slider = Arr::get($content, 'add_to_slider');
            if ($add_to_slider) {
                $price = nova_get_setting("{$add_to_slider}_slider_price");
                if ($user->balance < $price) {
                    return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
                }

                $user->subBalance($price, "Оплата публикации записи в слайдер");
                $user->update();

                if ($add_to_slider == "big") {
                    $post->is_slider = 1;
                } else if ($add_to_slider = "small") {
                    $post->is_featured = 1;
                }

                $post->update();
            }

            $post->categories()->attach($category->id);

            if ($post->created_at <= \Carbon\Carbon::now()) {
                try {
                    $post->is_notified = 1;
                    $post->update();

                    if ($user->followers()->exists()) {
                        foreach ($user->followers()->select('id')->get() as $follower) {
                            $follower->notify(new NewPost($post));
                        }
                    }
                } catch (\Exception $e) {
                    
                }
            }

            $draft->delete();

            return redirect("post/{$post->slug}")->with('success', $message);
        }

        return redirect()->route("new", ["step" => $request->step + 1, "id" => $draft->id]);
    }

    public function newSave(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
        ]);

        $user = auth()->user();
        $category = Category::find($request->category_id);

        if ($category->slug == 'sobitiya') {
            $request->validate([
                'event_date' => 'required',
            ]);
        }

        $post = new Post;
        $post->status = 0;

        if ($request->image) {
            $image = strtr($request->image, [
                'https://newshub.kz/storage/' => '',
                'http://newshub.kz/storage/' => '',
            ]);
            $image = ltrim($image, '/');
            $post->image = $image;
        }

        $post->image_caption = $request->image_caption;
        $post->user_id = $user->id;
        $post->author_id = $user->id;
        $post->event_date = $request->event_date;
        $post->created_at = $request->created_at?: date('Y-m-d H:i:s');
        $post->is_breaking = 0;

        $activeTitle = false;
        foreach ($request->title as $locale => $value) {
            if (!$activeTitle && $value) {
                $activeTitle = $value;
            }

            $post->setTranslation('title', $locale, $value);
        }

        if ($activeTitle) {
            $post->slug = \Str::slug($activeTitle, '-') . '-'. time();
        } else {
            $post->slug = \Str::uuid()->toString() . '-' . time();
        }

        foreach ($request->summary as $locale => $value) {
            $post->setTranslation('summary', $locale, $value);
        }

        foreach ($request->content as $locale => $value) {
            $post->setTranslation('content', $locale, clean($value));
        }

        if ($user->isModerator() || $user->isAdmin() || ($user->packageActive() && in_array($user->package->slug, ['standart-plus', 'standart-maximum']))) {
            $post->to_fcm = $request->to_fcm? 1: 0;
            $post->to_telegram = $request->to_telegram? 1: 0;
        }

        if ($request->get('files')) {
            $files = [];

            foreach ($request->get('files') as $file) {
                $file = '/' . ltrim(str_replace(url('/'), '', $file), '/');
                $files[] = [
                    "url" => $file,
                    "name" => basename($file),
                    "originalName" => basename($file)
                ];
            }

            $post->files = json_encode($files, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        if ($user->isModerator() || $user->isAdmin()) {
            $post->status = 1;
            $post->save();

            $message = __("Your news has been published");
        } else if ($user->packageActive() && $user->package_press && $category->slug == 'press-release-3') {
            $user->package_press -= 1;
            $user->update();

            $post->save();

            $message = __("Your news has been sent for moderation");
        } else if ($user->packageActive() && $user->package_events && $category->slug == 'sobitiya') {
            $user->package_events -= 1;
            $user->update();

            $post->save();

            $message = __("Your news has been sent for moderation");
        } else if ($user->packageActive() && $category->slug == 'intervyu') {
            $post->save();

            $message = __("Your news has been sent for moderation");
        } else {
            return redirect()->back()->with('error', __("To publish, you need to purchase a subscription"));
        }

        $user->addAction('create_post', [
            'post_id' => $post->id
        ]);

        if ($request->add_to_slider) {
            $price = nova_get_setting("{$request->add_to_slider}_slider_price");
            if ($user->balance < $price) {
                return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
            }

            $user->subBalance($price, "Оплата публикации записи в слайдер");
            $user->update();

            if ($request->add_to_slider == "big") {
                $post->is_slider = 1;
            } else if ($request->add_to_slider = "small") {
                $post->is_featured = 1;
            }

            $post->update();
        }

        if ($request->is_styled) {
            $price = nova_get_setting("style_card_price");
            if ($user->balance < $price) {
                return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
            }

            $user->subBalance($price, "Оплата цветной карточки поста");
            $user->update();

            $post->is_styled = 1;
            $post->style_color = $request->color;

            $post->update();
        }

        $post->categories()->attach($category->id);

        if ($post->created_at <= \Carbon\Carbon::now()) {
            try {
                $post->is_notified = 1;
                $post->update();

                if ($user->followers()->exists()) {
                    foreach ($user->followers()->select('id')->get() as $follower) {
                        $follower->notify(new NewPost($post));
                    }
                }
            } catch (\Exception $e) {
                
            }
        }

        return redirect()->back()->with('success', $message);
    }

    public function postResolve(Request $request, $uuid)
    {
        $user = auth()->user();
        abort_if(!$user->isAdmin() && !$user->isModerator(), 403);

        $post = Post::whereUuid($uuid)->first();
        if (!$post) {
            return redirect(route('home'))->with('warning', __('Post not found.'));
        }

        $post->status = 1;
        $post->update();

        $post->notify(new ChannelNotification($post));
        $post->notify(new NewPost($post));

        return redirect(url("post/{$post->slug}"))->with('success', __('Post published.'));
    }

    public function workspace(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user->isPress(), 403);

        $posts = $user->posts()
            // ->where('status', 1)
            // ->where('created_at', '<', \Carbon\Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->groupBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('workspace', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function actions(Request $request)
    {
        $user = auth()->user();
        abort_if(!$user->isPress(), 403);

        $actions = $user->actions()
            ->orderBy('created_at', 'DESC')
            ->paginate(15)
            ->withQueryString();

        return view('actions', [
            'user' => $user,
            'actions' => $actions,
        ]);
    }

    public function notifications(Request $request)
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->where('created_at', '<=', \Carbon\Carbon::now())
            ->orderBy('created_at', 'DESC')
            ->paginate(15)
            ->withQueryString();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return view('notifications', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    public function workspaceDelete(Request $request, $slug)
    {
        $user = auth()->user();
        abort_if(!$user->isPress(), 403);

        $post = $user->posts()->where('slug', $slug)->first();
        abort_if(!$post, 404);

        $post->delete();

        $user->addAction('delete_post');

        return redirect()->back()->with('success', trans("The :resource was deleted!", ['resource' => "«{$post->title}»"]));
    }

    public function workspacePost(Request $request, $slug)
    {
        $user = auth()->user();
        abort_if(!$user->isPress(), 403);

        $post = $user->posts()->where('slug', $slug)->first();
        abort_if(!$post, 404);

        // ~r($post->getTranslations('title'));

        if ($request->method() == 'POST') {
            $category = Category::find($request->category_id);
            $post->image = ltrim(str_replace(url('storage'), '', $request->image), '/');
            $post->keywords = $request->keywords;
            $post->image_caption = $request->image_caption;
            $post->created_at = $request->created_at?: date('Y-m-d H:i:s');
            $post->is_breaking = 0;

            foreach ($request->title as $locale => $value) {
                $post->setTranslation('title', $locale, $value);
            }

            foreach ($request->summary as $locale => $value) {
                $post->setTranslation('summary', $locale, $value);
            }

            foreach ($request->content as $locale => $value) {
                $post->setTranslation('content', $locale, clean($value));
            }

            if ($request->get('files')) {
                $files = [];

                foreach ($request->get('files') as $file) {
                    $file = '/' . ltrim(str_replace(url('/'), '', $file), '/');
                    $files[] = [
                        "url" => $file,
                        "name" => basename($file),
                        "originalName" => basename($file)
                    ];
                }

                $post->files = json_encode($files, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }

            if (
                $request->add_to_slider &&
                (
                    ($request->add_to_slider == "big" && !$post->is_slider) ||
                    ($request->add_to_slider == "small" && !$post->is_featured)
                )
            ) {
                $price = nova_get_setting("{$request->add_to_slider}_slider_price");
                if ($user->balance < $price) {
                    return redirect()->back()->with('error', __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."));
                }

                $user->subBalance($price, "Оплата публикации записи в слайдер");
                $user->update();

                if ($request->add_to_slider == "big") {
                    $post->is_slider = 1;
                } else if ($request->add_to_slider = "small") {
                    $post->is_featured = 1;
                }
            }

            $post->update();

            $post->categories()->attach($category->id);

            $user->addAction('update_post', [
                'post_id' => $post->id
            ]);

            return redirect()->back()->with("success", __("Saved."));
        }

        $categories = Category::select('categories.*')
            ->join('category_role', 'category_role.category_id', '=', 'categories.id')
            ->whereIn('category_role.role_id', $user->roles()->pluck('id'))
            ->groupBy('categories.id')
            ->get();

        return view('edit', [
            'user' => $user,
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function settings(Request $request)
    {
        $user = auth()->user();
        // $user->notify(new NewPost(Post::find(804)));

        return view('settings', [
            'user' => $user,
        ]);
    }

    public function settingsUpload(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) {
            $uuid = \Str::uuid()->toString();
            $extension = $request->image->getClientOriginalExtension();

            $fileName =  "{$uuid}.{$extension}";
            $request->image->storeAs('image', $fileName, 'public');

            return response()->json([
                "image" => "/image/{$fileName}"
            ]);
        }
    }

    public function settingsBasic(Request $request)
    {
        $user = \Auth::user();

        $request->validate([
            'name' => 'required',
        ]);

        if ($user->isPress()) {
            $request->validate([
                'user_category_id' => 'required',
                'description' => 'required',
            ]);

            $user->user_category_id = $request->user_category_id;
            $user->description = $request->description;
            $user->bin = $request->bin;
            $user->iban = $request->iban;
            $user->bank = $request->bank;
            $user->bik = $request->bik;
            $user->kbe = $request->kbe;
        }

        if ($user->isUser()) {
            $request->validate([
                'lastname' => 'required',
            ]);

            $user->lastname = $request->lastname;
        }

        $user->name = $request->name;
        $user->avatar = ltrim(str_replace(url('storage'), '', $request->avatar), '/');

        $user->save();

        $user->addAction('update_profile');

        return redirect()->back()->with("success", __("Saved."));
    }

    public function settingsContacts(Request $request)
    {
        $user = \Auth::user();

        if (strcmp($user->email, $request->email) !== 0) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);
        }

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        $user->addAction('update_contacts');

        return redirect()->back()->with("success", __("Saved."));
    }

    public function settingsAccess(Request $request)
    {
        if (!(\Hash::check($request->get('current-password'), \Auth::user()->password))) {
            return redirect()->back()->with("error", __("The provided password does not match your current password."));
        }

        $request->validate([
            'current-password' => 'required',
            'new-password'     => 'required|string|min:6|confirmed',
        ]);

        $user = \Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        $user->addAction('update_password');

        return redirect()->back()->with("success", __("Saved."));
    }

    public function registerRole(Request $request)
    {
        $user = auth()->user();

        if ($user->roles()->exists()) {
            return redirect('profile')->with("warning", __("Role already selected."));
        }

        if ($request->method() == 'POST') {
            if (in_array($request->role, ['press', 'journalist'])) {
                $user->assignRole($request->role);
            } else {
                $user->assignRole('journalist');
            }

            // add balance before 1 jan 2022  && time() < strtotime('2022-01-01 00:00:00')
            if ($request->role == 'press') {
                $user->balance = 125000;
                $user->update();

                $admins = User::select('users.*')
                    ->join('role_user', 'role_user.user_id', 'users.id')
                    ->join('roles', 'role_user.role_id', 'roles.id')
                    ->where('roles.slug', 'admin')
                    ->get();

                foreach ($admins as $admin) {
                    $admin->notify(new NewPress($user));
                }
            }

            if ($request->role == 'journalist') {
                return redirect('register/place');
            } else {
                return redirect('settings');
            }
        }

        return view('auth.register-role');
    }

    public function registerPlace(Request $request)
    {
        $user = auth()->user();

        if (!$user->isUser()) {
            return redirect('profile');
        }

        if ($request->method() == 'POST') {
            $city = City::find($request->city);
            if (!$city) {
                return redirect()->back()->with("error", __("Incorrect City."));
            }

           // $user->name = preg_replace('@[^A-ZА-Яa-zа-я\-\ ]@Usi', '', $request->name);
        //    $user->lastname = preg_replace('@[^A-ZА-Яa-zа-я\-\ ]@Usi', '', $request->lastname);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->city_id = $city->id;
            $user->update();

            return redirect('profile')->with('success', __("Saved."));
        }

        $regions = Region::all();
        $cities = City::all();

        return view('auth.register-place', [
            'regions' => $regions,
            'cities' => $cities,
        ]);
    }


    public function pollRequest(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'photo' => 'required',
        ]);

        $poll = Poll::whereSlug($slug)->first();

        if (!$poll) {
            return response()->json([
                'ok' => false,
                'message' => __('Not found.')
            ]);
        }
        
        if ($poll->requests()->where('user_id', auth()->user()->id)->exists()) {
            return response()->json([
                'ok' => false,
                'message' => __('You have already submitted a request.')
            ]);
        }

        $participant = new PollRequest;
        $participant->user_id = auth()->user()->id;
        $participant->name = $request->name;
        $participant->position = $request->position;
        $participant->phone = $request->phone;
        $participant->email = $request->email;
        $participant->photo = ltrim(str_replace(url('storage'), '', $request->photo), '/');
        $poll->requests()->save($participant);

        $admins = User::select('users.*')
            ->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.slug', 'admin')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewPollRequest($participant));
        }

        return response()->json([
            'ok' => true,
        ]);
    }

    public function pollVote(Request $request, $slug)
    {
        $request->validate([
            'participant' => 'required|numeric',
        ]);

        abort_if(!auth()->user()->isUser(), 403);

        $poll = Poll::whereSlug($slug)->first();
        abort_if(!$poll, 404);

        if ($poll->votes()->where('user_id', auth()->user()->id)->exists()) {
            return redirect()->back()->with('warning', __("You have already voted."));
        }

        $participant = $poll->requests()->where('id', $request->participant)->first();
        if (!$participant) {
            return redirect()->back()->with('warning', __("Unknown participant."));
        }

        $vote = new PollVote;
        $vote->user_id = auth()->user()->id;
        $vote->poll_request_id = $participant->id;
        $vote->ip = $request->ip();
        $poll->votes()->save($vote);

        return redirect()->back()->with('success', __("You voted successfully."));
    }
}
