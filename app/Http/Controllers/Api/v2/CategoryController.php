<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Rubric;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function category(Request $request)
    {
        $request->validate([
            'slug' => 'required',
        ]);
        sleep(1);

        $query = Post::select(
                'posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.image_md', 'posts.image_sm', 'posts.image_blur', 'posts.pageviews', 'posts.summary', 'posts.created_at', 'users.name', 'users.avatar', 'users.avatar_sm',
            )
            // ->join('category_post', 'category_post.post_id', 'posts.id')
            ->join('users', 'users.id', 'posts.user_id')
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now());

        $category = Category::select('id')->where('slug', $request->slug)->first();
        abort_if(!$category, 404);
    
        if ($request->from != 'index' || $request->slug != 'news') {
            $query->whereExists((function($query) use ($category) {
                $query->select(\DB::raw(1))
                    ->from('category_post')
                    ->whereColumn('category_post.post_id', 'posts.id')
                    ->where('category_post.category_id', $category->id);
            }));
        }

        if ($request->rubric) {
            $rubric = Rubric::select('id')->where('slug', $request->rubric)->first();
            abort_if(!$rubric, 404);

            $query->whereExists((function($query) use ($rubric) {
                $query->select(\DB::raw(1))
                    ->from('post_rubric')
                    ->whereColumn('post_rubric.post_id', 'posts.id')
                    ->where('post_rubric.rubric_id', $rubric->id);
            }));
            // $query->join("post_rubric", "posts.id", "=", "post_rubric.post_id")
            //     // ->join("rubrics", "rubrics.id", "=", "post_rubric.rubric_id")
            //     ->where("post_rubric.rubric_id", $rubric->id);
        }

        // $query->dd();

        $posts = $query
            ->orderByDesc('posts.id')
            ->cursorPaginate(10);

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function tag(Request $request, $tag)
    {
        $query = Post::where('status', 1)
            ->where('created_at', '<', Carbon::now());

        $query->where('keywords', 'like', "%{$tag}%");

        $posts = $query->latest('created_at')
            ->groupBy('id')
            ->cursorPaginate(10);

        return response()->json([
            'ok' => true,
            'tag' => $tag,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function rubric(Request $request, $slug, $rubricSlug)
    {
        $category = Category::where('slug', $slug)
            ->first();
        abort_if(!$category, 404);

        $rubric = Rubric::where('slug', $rubricSlug)->first();
        abort_if(!$rubric, 404);

        $posts = $category->posts()
            ->select(
                'posts.id', 'posts.title', 'posts.slug', 'posts.user_id', 'posts.image', 'posts.image_md', 'posts.image_sm', 'posts.image_blur', 'posts.pageviews', 'posts.summary', 'posts.created_at', 'users.name', 'users.avatar', 'users.avatar_sm',
            )
            ->join("post_rubric", "posts.id", "=", "post_rubric.post_id")
            ->join("rubrics", "rubrics.id", "=", "post_rubric.rubric_id")
            ->where("rubrics.slug", $rubricSlug)
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now())
            ->latest('posts.created_at')
            ->cursorPaginate(10);

        return response()->json([
            'ok' => true,
            'posts' => $posts,
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

}
