<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Vacancy as VacancyResource;
use App\Models\Category;
use App\Models\Vacancy;
use App\Models\User;
use App\Models\Region;

class HomeController extends Controller
{
    public function posts(Request $request, $id = false)
    {
        $translatable = (new Post)->getTranslatableAttributes();

        $query = Post::query();
        $query->where('status', 1);
        $query->with(['user', 'categories']);
        $query->where('created_at', '<=', date('Y-m-d H:i:s'));

        if ($id) {
            $query->where('id', $id);

            $post = $query->first();
            abort_if(!$post, 404, "Not found post");

            $post->pageviews++;
            $post->update();

            $post = $post->toArray();
            $row = json_decode(json_encode($post));

            if (property_exists($row, 'image')) {
                $row->image = $row->image? asset("storage/{$row->image}"): '';
            }

            if (property_exists($row, 'user')) {
                $row->user->avatar = $row->user->avatar? asset("storage/{$row->user->avatar}"): '';
            }

            if (property_exists($row, 'categories')) {
                foreach ($row->categories as $category) {
                    $category->name = json_decode($category->name);
                }
            }

            if (property_exists($row, 'files')) {
                $row->files = json_decode($row->files);

                $files = [];
                if ($row->files) {
                    foreach ($row->files as $file) {
                        $files[] = "<a href='" . route('file', ['slug' => $row->slug, 'name' => md5(basename($file->name))]) . "'  download>" . $file->originalName . "</a>";
                    }
                }
                $row->files = implode("<br/>", $files);
            }

            foreach ($row as $key => $val) {
                if (in_array($key, $translatable)) {
                    $row->{$key} = json_decode($val);
                }
            }

            if (property_exists($row, 'content')) {
                foreach ($row->content as $lang => $content) {
                    $row->content->{$lang} = str_replace("\"/storage/", '"' . asset("storage") . '/', $content);
                }
            }

            if (is_null($row->event_date)) {
                $row->event_date = date('Y-m-d H:i:s');
            }

            $row->link = url("post/{$row->slug}");
            $row->tags = [];
            $row->td_video = "";

            return $this->jsonResponse($row);
        }

        if ($request->tags == 6) {
            $query->where('is_featured', 1);
        }

        if ($request->categories) {
            $query->join('category_post', 'category_post.post_id', 'posts.id');
            $query->whereIn('category_post.category_id', $request->categories);
        }

        if ($request->search) {
            $token = $request->search;
            $query->where(function($query) use ($token) {
                $query->where('title', 'like', "%{$token}%")
                    ->orWhere('content', 'like', "%{$token}%");
            });
        }

        $query->orderBy($request->orderby?: 'created_at', $request->order == 'asc'? 'asc': 'desc');
        $query->groupBy('posts.id');

        $posts = $query
            ->paginate($request->per_page?: 10, $request->_fields? explode(',', $request->_fields): ['*'])
            ->withQueryString();

        $resource = PostResource::collection($posts);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                if (property_exists($row, 'image')) {
                    $row->image = $row->image? asset("storage/{$row->image}"): '';
                }

                if (property_exists($row, 'user')) {
                    $row->user->avatar = $row->user->avatar? asset("storage/{$row->user->avatar}"): '';
                }

                if (property_exists($row, 'categories')) {
                    foreach ($row->categories as $category) {
                        $category->name = json_decode($category->name);
                    }
                }

                if (property_exists($row, 'files')) {
                    $row->files = json_decode($row->files);

                    $files = [];
                    if ($row->files) {
                        foreach ($row->files as $file) {
                            $files[] = "<a href='" . route('file', ['slug' => $row->slug, 'name' => md5(basename($file->name))]) . "'  download>" . $file->originalName . "</a>";
                        }
                    }
                    $row->files = implode("<br/>", $files);
                }

                foreach ($row as $key => $val) {
                    if (in_array($key, $translatable)) {
                        $row->{$key} = json_decode($val);
                    }
                }

                if (property_exists($row, 'content')) {
                    foreach ($row->content as $lang => $content) {
                        $row->content->{$lang} = str_replace("\"/storage/", '"' . asset("storage") . '/', $content);
                    }
                }

                if (is_null($row->event_date)) {
                    $row->event_date = date('Y-m-d H:i:s');
                }
    
                $row->link = url("post/{$row->slug}");
                $row->tags = [1];
                $row->td_video = "";
            }
        }

        // $response->setJson(json_encode($data->data, $this->jsonOptions));

        return $this->jsonResponse($data->data);
    }

    public function popularPosts(Request $request)
    {
        $range = $request->range?: 7;

        $query = Post::query();
        $query->with(['user', 'categories']);
        $query->where('status', 1);
        $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime("-{$range} day")));

        if ($request->categories) {
            $query->join('category_post', 'category_post.post_id', 'posts.id');
            $query->whereIn('category_post.category_id', $request->categories);
        }

        if ($request->search) {
            $token = $request->search;
            $query->where(function($query) use ($token) {
                $query->where('title', 'like', "%{$token}%")
                    ->orWhere('content', 'like', "%{$token}%");
            });
        }

        $posts = $query
            ->orderBy('pageviews', 'desc')
            ->paginate($request->per_page?: 10, $request->_fields? explode(',', $request->_fields): ['*'])
            ->withQueryString();

        $translatable = (new Post)->getTranslatableAttributes();
        $resource = PostResource::collection($posts);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                if (property_exists($row, 'image')) {
                    $row->image = $row->image? asset("storage/{$row->image}"): '';
                }

                if (property_exists($row, 'user')) {
                    $row->user->avatar = $row->user->avatar? asset("storage/{$row->user->avatar}"): '';
                }

                if (property_exists($row, 'categories')) {
                    foreach ($row->categories as $category) {
                        $category->name = json_decode($category->name);
                    }
                }

                if (property_exists($row, 'files')) {
                    $row->files = json_decode($row->files);

                    $files = [];
                    if ($row->files) {
                        foreach ($row->files as $file) {
                            $files[] = "<a href='" . route('file', ['slug' => $row->slug, 'name' => md5(basename($file->name))]) . "'  download>" . $file->originalName . "</a>";
                        }
                    }
                    $row->files = implode("<br/>", $files);
                }

                foreach ($row as $key => $val) {
                    if (in_array($key, $translatable)) {
                        $row->{$key} = json_decode($val);
                    }
                }

                if (property_exists($row, 'content')) {
                    foreach ($row->content as $lang => $content) {
                        $row->content->{$lang} = str_replace("\"/storage/", '"' . asset("storage") . '/', $content);
                    }
                }

                if (is_null($row->event_date)) {
                    $row->event_date = date('Y-m-d H:i:s');
                }
    
                $row->link = url("post/{$row->slug}");
                $row->tags = [];
                $row->td_video = "";
            }
        }

        // $response->setJson(json_encode($data->data, $this->jsonOptions));

        return $this->jsonResponse($data->data);
    }

    public function categories(Request $request)
    {
        $query = Category::query();

        $query->orderBy($request->orderby?: 'created_at', $request->order == 'asc'? 'asc': 'desc');

        $categories = $query
            ->paginate($request->per_page?: 100)
            ->withQueryString();

        $translatable = (new Category)->getTranslatableAttributes();
        $resource = CategoryResource::collection($categories);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                foreach ($row as $key => $val) {
                    if (in_array($key, $translatable)) {
                        $row->{$key} = json_decode($val);
                    }
                }
            }
        }

        // $response->setJson(json_encode($data->data, $this->jsonOptions));

        return $this->jsonResponse($data->data);
    }

    public function vacancies(Request $request)
    {
        $query = Vacancy::query();

        $query->orderBy($request->orderby?: 'created_at', $request->order == 'asc'? 'asc': 'desc');

        $vacancies = $query
            ->paginate($request->per_page?: 100)
            ->withQueryString();

        $translatable = (new Vacancy)->getTranslatableAttributes();
        $resource = VacancyResource::collection($vacancies);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                foreach ($row as $key => $val) {
                    if (in_array($key, $translatable)) {
                        $row->{$key} = json_decode($val);
                    }
                }
            }
        }

        // $response->setJson(json_encode($data->data, $this->jsonOptions));

        return $this->jsonResponse($data->data);
    }

    public function regions(Request $request)
    {
        $regions = \App\Models\Region::select('id', 'region_name_ru', 'code')->get();

        return $this->jsonResponse([
            'region' => $regions,
        ]);
    }

    public function cities(Request $request)
    {
        $cities = \App\Models\City::select('id', 'region_id', 'city_name_ru')->get();

        return $this->jsonResponse([
            'city' => $cities,
        ]);
    }

    public function userFollow(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $me = auth()->user();
        if (!$me->feeds()->where('user_id', $user->id)->exists()) {
            $me->feeds()->attach($user->id);
        }

        return $this->jsonResponse([
            'status' => true,
            'message' => __("You have successfully subscribed to the press center news") . " {$user->name}"
        ]);
    }

    public function users(Request $request, $role = false)
    {
        $query = User::query()
            ->with('city')
            ->select(
                'users.id',
                'users.name',
                'users.lastname',
                'users.description',
                'users.avatar',
                'users.city_id',
            )
            ->whereNotNull('users.name')
            ->whereNotNull('users.lastname');

        if (in_array($role, ['moderator', 'press', 'journalist', 'reader'])) {
            $query->join('role_user', 'users.id', 'role_user.user_id')
                ->join('roles', 'roles.id', 'role_user.role_id')
                ->where('roles.slug', $role);
        }

        $users = $query
            ->orderBy($request->orderby?: 'users.created_at', $request->order == 'asc'? 'asc': 'desc')
            ->groupBy('users.id')
            ->paginate($request->per_page?: 10, $request->_fields? explode(',', $request->_fields): ['*'])
            ->withQueryString();

        $resource = UserResource::collection($users);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                if (property_exists($row, 'avatar')) {
                    $row->avatar = $row->avatar? asset("storage/{$row->avatar}"): '';
                }
            }
        }

        return $this->jsonResponse($data->data);
    }

    public function userUnfollow(Request $request, $id)
    {
        $user = User::find($id);
        abort_if(!$user, 404);

        $me = auth()->user();
        $me->feeds()->detach($user->id);

        return $this->jsonResponse([
            'status' => true,
            'message' => __("You have successfully unsubscribed from the press center news") . " {$user->name}"
        ]);
    }

    public function feed(Request $request)
    {
        $translatable = (new Post)->getTranslatableAttributes();

        $user = auth()->user();

        $query = Post::query();
        $query->select("posts.*");
        $query->join('followers', 'followers.user_id', '=', 'posts.user_id');
        $query->where('followers.follower_id', $user->id);
        $query->where('posts.status', 1);
        $query->where('posts.created_at', '<', \Carbon\Carbon::now());

        if ($request->tags == 6) {
            $query->where('posts.is_featured', 1);
        }

        if ($request->categories) {
            $query->join('category_post', 'category_post.post_id', 'posts.id');
            $query->whereIn('category_post.category_id', $request->categories);
        }

        if ($request->search) {
            $token = $request->search;
            $query->where(function($query) use ($token) {
                $query->where('posts.title', 'like', "%{$token}%")
                    ->orWhere('posts.content', 'like', "%{$token}%");
            });
        }

        $query->orderBy($request->orderby?: 'posts.created_at', $request->order == 'asc'? 'asc': 'desc');
        $query->groupBy('posts.id');

        $posts = $query
            ->paginate($request->per_page?: 10, $request->_fields? explode(',', $request->_fields): ['*'])
            ->withQueryString();

        $resource = PostResource::collection($posts);
        $response = $resource->toResponse($request);
        $data = $response->getData();

        if (property_exists($data, 'data')) {
            foreach ($data->data as $row) {
                if (property_exists($row, 'image')) {
                    $row->image = $row->image? asset("storage/{$row->image}"): '';
                }

                if (property_exists($row, 'user')) {
                    $row->user->avatar = $row->user->avatar? asset("storage/{$row->user->avatar}"): '';
                }

                if (property_exists($row, 'categories')) {
                    foreach ($row->categories as $category) {
                        $category->name = json_decode($category->name);
                    }
                }

                if (property_exists($row, 'files')) {
                    $row->files = json_decode($row->files);

                    $files = [];
                    if ($row->files) {
                        foreach ($row->files as $file) {
                            $files[] = "<a href='" . route('file', ['slug' => $row->slug, 'name' => md5(basename($file->name))]) . "'  download>" . $file->originalName . "</a>";
                        }
                    }
                    $row->files = implode("<br/>", $files);
                }

                foreach ($row as $key => $val) {
                    if (in_array($key, $translatable)) {
                        $row->{$key} = json_decode($val);
                    }
                }

                if (property_exists($row, 'content')) {
                    foreach ($row->content as $lang => $content) {
                        $row->content->{$lang} = str_replace("\"/storage/", '"' . asset("storage") . '/', $content);
                    }
                }

                if (is_null($row->event_date)) {
                    $row->event_date = date('Y-m-d H:i:s');
                }

                $row->link = url("post/{$row->slug}");
                $row->tags = [];
                $row->td_video = "";
            }
        }

        return $this->jsonResponse($data->data);
    }

}
