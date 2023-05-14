<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\UserCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users(Request $request)
    {
        return response()->json([
            'ok' => true,
            'users' => [],
        ]);
    }

    public function category(Request $request, $slug)
    {
        $category = UserCategory::select('id')->where('slug', $slug)->first();
        abort_if(!$category, 404);

        $users = $category->users()
            ->select('users.id', 'users.name', 'users.description', 'users.avatar')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.slug', 'press')
            ->latest('users.created_at')
            ->get()
            ->map(function($item) {
                $item->description = \Str::limit($item->description, 200, '...');
                return $item;
            });

        return response()->json([
            'ok' => true,
            'users' => $users,
        ]);
    }

    public function user(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $user->role_names = $user->roles()->select('name')->where('slug', '!=', 'admin')->pluck('name');

        return response()->json([
            'ok' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function posts(Request $request, $id)
    {
        $user = User::select('id')->find($id);
        abort_if(!$user, 404);

        $posts = Post::select(
                'posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.summary', 'posts.created_at', 'users.name', 'users.avatar',
            )
            ->join('users', 'users.id', 'posts.user_id')
            ->where('users.id', $user->id)
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now())
            ->orderByDesc('posts.id')
            ->cursorPaginate(10);

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ]);
    }

    public function follow(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $me = auth('sanctum')->user();

        $is_follow = $me->feeds()->where('users.id', $user->id)->exists();
        $message = '';

        if ($request->type == 1 && !$is_follow) {
            $me->feeds()->attach($user);
            $message = __("You have successfully subscribed to the press center news") . " {$user->name}";
        } else if (!$request->type && $is_follow) {
            $me->feeds()->detach($user);
            $message = __("You have successfully unsubscribed from the press center news") . " {$user->name}";
        }

        return response()->json([
            'ok' => true,
            'message' => $message
        ]);
    }

    public function actions(Request $request)
    {
        $user = auth('sanctum')->user();

        $query = $user->actions()->select('created_at', 'type', 'content');

        if ($request->search) {
            $token = $request->search;
            // $query->where(function($query) use ($token) {
            //     $query->where("apps.name", "like", "%{$token}%")
            //         ->orWhere("apps.url", "like", "%{$token}%")
            //         ->orWhere("apps.campaign", "like", "%{$token}%");
            // });
        }

        if ($request->sort) {
            $query->orderBy($request->sort, $request->order? $request->order: 'asc');
        } else {
            $query->latest();
        }

        $actions = $query
            ->paginate($request->per_page?: 15)
            ->withQueryString();

        foreach ($actions as $action) {
            $action->name = $action->getLabel();
        }

        return response()->json([
            'ok' => true,
            'actions' => $actions,
        ]);
    }

    public function workspace(Request $request)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user || !$user->isPress(), 403);

        $query = $user->posts()->select('id', 'title', 'slug', 'created_at');

        if ($request->search) {
            $token = $request->search;
            $query->where(function($query) use ($token) {
                $query->where("posts.title", "like", "%{$token}%")
                    ->orWhere("posts.summary", "like", "%{$token}%")
                    ->orWhere("posts.content", "like", "%{$token}%");
            });
        }

        if ($request->sort) {
            $query->orderBy($request->sort, $request->order? $request->order: 'asc');
        } else {
            $query->orderByDesc('posts.id');
        }

        $posts = $query
            ->paginate($request->per_page?: 15)
            ->withQueryString();

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ]);
    }

    public function postDelete(Request $request, $slug)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user || !$user->isPress(), 403);

        $post = $user->posts()->where('slug', $slug)->first();
        abort_if(!$post, 404);

        $post->delete();

        $user->addAction('delete_post');

        return redirect()->back()->with('success', trans("The :resource was deleted!", ['resource' => "«{$post->title}»"]));
    }

    public function postUpdate(Request $request, $slug)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user || !$user->isPress(), 403);

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

        // $categories = Category::select('categories.*')
        //     ->join('category_role', 'category_role.category_id', '=', 'categories.id')
        //     ->whereIn('category_role.role_id', $user->roles()->pluck('id'))
        //     ->groupBy('categories.id')
        //     ->get();

        return response()->json([
            'ok' => true,
            'post' => $post,
            // 'categories' => $categories,
        ]);
    }
}
