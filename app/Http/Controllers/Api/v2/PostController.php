<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Jobs\PostImageJob;
use App\Models\Category;
use App\Models\GrammaticalError;
use App\Models\Post;
use App\Models\Widget;
use App\Notifications\ChannelNotification;
use App\Notifications\NewPost;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $breaking = Post::where('is_breaking', 1)
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $slider = Post::where('is_slider', 1)
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now())
            ->latest('created_at')
            ->limit(10)
            ->get();

        $featured = Post::where('is_featured', 1)
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now())
            ->latest('created_at')
            ->limit(4)
            ->get();

        $widgets = Widget::orderBy('position')
            ->get();

        return response()->json([
            'ok' => true,
            'breaking' => $breaking,
            'slider' => $slider,
            'featured' => $featured,
            'widgets' => $widgets,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function feed(Request $request)
    {
        $user = auth('sanctum')->user();

        $query = Post::query();
        $query->select('posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.image_md', 'posts.image_sm', 'posts.image_blur', 'posts.pageviews', 'posts.summary', 'posts.created_at', 'posts.event_date', 'posts.article_type', 'users.name', 'users.avatar', 'users.avatar_sm');
        $query->join('followers', 'followers.user_id', '=', 'posts.user_id');
        $query->join('users', 'users.id', '=', 'posts.user_id');
        $query->where('followers.follower_id', $user->id);
        $query->where('posts.status', 1);
        $query->where('posts.created_at', '<', Carbon::now());

        $posts = $query
            ->orderBy('posts.id', 'DESC')
            ->cursorPaginate(10);

        foreach ($posts as $post) {
            $post->categoriesSlugs = $post->categories()->select('slug')->groupBy('slug')->pluck('slug')->toArray(); 
        }

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function postGrammar(Request $request)
    {
        $post = Post::where('slug', $request->slug)
            ->first();

        abort_if(!$post, 404);

        $grammar = new GrammaticalError;
        $grammar->post_id = $post->id;

        if (!auth('sanctum')->guest()) {
            $grammar->user_id = auth('sanctum')->user()->id;
        }
        $grammar->error = $request->error;
        $grammar->suggestion = $request->suggestion;
        $grammar->save();

        return response()->json(['ok' => true]);
    }

    public function post(Request $request, $slug)
    {
        $query = Post::select('posts.*', 'users.avatar', 'users.avatar_sm', 'users.name', 'users.description')
            ->join('users', 'users.id', 'posts.user_id')
            ->where('posts.slug', $slug);

        $user = auth('sanctum')->user();
        if ($user) {
            $query->selectRaw("(SELECT 1 FROM post_favorite WHERE post_favorite.user_id = {$user->id} AND post_favorite.post_id = posts.id) as is_favorite");
        }

        $post = $query->first();
        abort_if(!$post, 404);

        $post->pageviews++;
        $post->save(['timestamps' => false]);

        if ($user) {
            $notification = $user
                ->unreadNotifications()
                ->select('id', 'read_at')
                ->whereHasMorph('targetable', $post::class, function (Builder $query) use ($post) {
                    $query->where('targetable_id', $post->id);
                })
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        }

        $post->categories = $post->categories()->select('slug', 'name')->groupBy('slug')->get();
        $post->rubrics = $post->rubrics()->select('slug', 'name')->groupBy('slug')->get();

        $nextPost = $post->previousPost();
        $post->next = $nextPost? $nextPost->slug: null;

        $post->content = preg_replace_callback('@<img src="/storage/([^\"]+)"@Usi', function($match) {
            return '<img src="' . asset('/storage/' . $match[1]) . '"';
        }, $post->content);

        return response()->json([
            'ok' => true,
            'post' => $post,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function editor(Request $request)
    {
        $request->validate([
            'slug' => 'required',
        ]);

        $user = auth('sanctum')->user();
        abort_if(!$user->isPress(), 403);

        $post = $user->posts()->where('slug', $request->slug)->first();
        abort_if(!$post, 404);

        $post->embedsSanitize();

        $data = [];
        foreach ($post->getAttributes() as $key => $attr) {
            if (in_array($key, $post->translatable)) {
                $data[$key] = json_decode($attr, true);
            } else {
                $data[$key] = $attr;
            }
        }

        $category = $post->categories()->select('id')->first();
        if ($category) {
            $data['category_id'] = $category->id;
        }

        return response()->json([
            'ok' => true,
            'post' => $data,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function postAmp(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->first();

        abort_if(!$post, 404);

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
        $post->save(['timestamps' => false]);

        if (!auth('sanctum')->guest()) {
            $user = auth('sanctum')->user();

            $notification = $post
                ->getUnreadNotifications()
                ->select('id', 'read_at')
                ->whereHasMorph('notifiable', $user::class, function (Builder $query) use ($user) {
                    $query->where('notifiable_id', $user->id);
                })
                ->first();

            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json([
            'ok' => true,
            'post' => $post,
            'schema' => $schema,
        ]);
    }

    public function morePosts(Request $request, $offset)
    {
        $category = Category::where('slug', 'news')->first();

        $posts = $category->posts()
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now())
            ->latest('created_at')
            ->groupBy('id')
            ->skip($offset)
            ->take(10)
            ->get();

        foreach ($posts as $post) {
            $post->categoriesSlugs = $post->categories()->select('slug')->groupBy('slug')->pluck('slug')->toArray(); 
        }

        return response()->json([
            'ok' => true,
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
            $prevPost->save(['timestamps' => false]);

            return response()->json([
                'ok' => true,
                'post' => $prevPost,
            ]);
        }

        return response()->json([
            'ok' => false,
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

    public function search(Request $request)
    {
        $query = Post::select('title', 'summary', 'slug', 'image', 'content')
            ->where('status', 1)
            ->where('created_at', '<', Carbon::now());

        if ($request->q) {
            $token = $request->q;
            $query->where(function($query) use ($token) {
                $query->where('title', 'like', "%{$token}%")
                    ->orWhere('content', 'like', "%{$token}%");
            });
        }

        $posts = $query->latest('created_at')
            ->groupBy('id')
            ->cursorPaginate(10);

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ]);
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


    public function new(Request $request, $step = 1, $id = 0)
    {
        $user = auth('sanctum')->user();

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

    public function save(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'image' => 'required',
            'image_caption' => 'required',
            'content' => 'required',
        ]);

        $user = auth('sanctum')->user();
        $category = Category::find($request->category_id);

        if ($category->slug == 'sobitiya') {
            $request->validate([
                'event_date' => 'required',
            ]);
        }

        // dd($request->all());

        $post = new Post;
        $post->status = $user->is_auto_moderate? 1: 0;

        if ($request->image) {
            $image = strtr($request->image, [
                'https://api.newshub.kz/storage/' => '',
                'https://newshub.kz/storage/' => '',
                '/storage/' => '',
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
            return response()->json([
                'ok' => false,
                'message' => __("To publish, you need to purchase a subscription"),
            ]);
        }

        $user->addAction('create_post', [
            'post_id' => $post->id
        ]);

        if ($request->add_to_slider) {
            $price = nova_get_setting("{$request->add_to_slider}_slider_price");
            if ($user->balance < $price) {
                return response()->json([
                    'ok' => false,
                    'message' => __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."),
                ]);
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
                return response()->json([
                    'ok' => false,
                    'message' => __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."),
                ]);
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
                    foreach ($user->followers()->select('id')->where('newsletter', 1)->get() as $follower) {
                        $follower->notify(new NewPost($post));
                    }
                }
            } catch (\Exception $e) {
                
            }
        }

        // PostImageJob::dispatch($post);

        return response()->json([
            'ok' => true,
            'slug' => $post->slug,
            'message' => $message,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required',
            'image' => 'required',
            'content' => 'required',
        ]);

        $user = auth('sanctum')->user();
        abort_if(!$user || !$user->isPress(), 403);

        $post = $user->posts()->where('slug', $request->slug)->first();
        abort_if(!$post, 404);

        $category = Category::find($request->category_id);

        if (!$post->status && $user->is_auto_moderate) {
            $post->status = 1;
        }

        if ($request->image) {
            $image = strtr($request->image, [
                'https://api.newshub.kz/storage/' => '',
                'https://newshub.kz/storage/' => '',
                '/storage/' => '',
            ]);
            $image = ltrim($image, '/');

            if ($image && $post->image != $image) {
                $post->image = $image;
                $post->image_md = null;
                $post->image_sm = null;
                $post->image_fit = null;
                $post->image_blur = null;
            }
        }

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

        // if ($request->get('files')) {
        //     $files = [];

        //     foreach ($request->get('files') as $file) {
        //         $file = '/' . ltrim(str_replace(url('/'), '', $file), '/');
        //         $files[] = [
        //             "url" => $file,
        //             "name" => basename($file),
        //             "originalName" => basename($file)
        //         ];
        //     }

        //     $post->files = json_encode($files, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // }

        if (
            $request->add_to_slider &&
            (
                ($request->add_to_slider == "big" && !$post->is_slider) ||
                ($request->add_to_slider == "small" && !$post->is_featured)
            )
        ) {
            $price = nova_get_setting("{$request->add_to_slider}_slider_price");
            if ($user->balance < $price) {
                return response()->json([
                    'ok' => false,
                    'message' => __("Insufficient funds on the balance sheet. Top up the balance in your account and try again."),
                ]);
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

        PostImageJob::dispatch($post);

        return response()->json([
            'ok' => true,
            'message' => __("Saved."),
        ]);
    }

    public function postResolve(Request $request, $uuid)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user->isAdmin() && !$user->isModerator(), 403);

        $post = Post::select('id', 'status', 'user_id', 'created_at', 'is_notified')->whereSlug($uuid)->first();
        if (!$post) {
            return response()->json([
                'ok' => false,
                'message' => __('Post not found'),
            ]);
        }

        $now = Carbon::now();

        $post->status = 1;

        if ($post->created_at < $now) {
            $post->created_at = $now;
        }

        $post->update();

        // if ($post->created_at <= Carbon::now()) {
        //     if (!$post->is_notified) {
        //         try {
        //             $post->notify(new ChannelNotification($post));

        //             $post->is_notified = 1;
        //             $post->update();

        //             $author = $post->user()->select('id')->first();

        //             if ($author->followers()->exists()) {
        //                 foreach ($author->followers()->select('id')->where('newsletter', 1)->get() as $follower) {
        //                     $follower->notify(new NewPost($post));
        //                 }
        //             }
        //         } catch (\Exception $e) {
                    
        //         }
        //     }
        // }

        return response()->json([
            'ok' => true,
            'message' => __('Post published'),
        ]);
    }
}
