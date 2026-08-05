<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Jobs\UserImageJob;
use App\Models\Post;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function user()
    {
        $user = auth('sanctum')->user();

        return response()->json([
            'ok' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function subscriptions(Request $request)
    {
        $user = auth('sanctum')->user();

        return response()->json([
            'ok' => true,
            'feeds' => $user->feeds()->select('users.id')->pluck('id'),
        ]);
    }

    public function notifications(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'ok' => false,
            ]);
        }

        $query = $user->notifications()->where('created_at', '<=', Carbon::now());

        $notifications = $query
            ->paginate($request->per_page?: 15)
            ->withQueryString();

        return response()->json([
            'ok' => true,
            'notifications' => $notifications,
            'unread_count' => $this->unreadNotificationsCount($user),
        ]);
    }

    public function notificationRead(Request $request, string $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'ok' => false,
            ]);
        }

        $notification = $user->notifications()
            ->where('created_at', '<=', Carbon::now())
            ->findOrFail($id);

        if (!$notification->read()) {
            $notification->markAsRead();
        }

        return response()->json([
            'ok' => true,
            'unread_count' => $this->unreadNotificationsCount($user),
        ]);
    }

    public function notificationsRead(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'ok' => false,
            ]);
        }

        $user->unreadNotifications()
            ->where('created_at', '<=', Carbon::now())
            ->update([
                'read_at' => Carbon::now(),
            ]);

        return response()->json([
            'ok' => true,
            'unread_count' => $this->unreadNotificationsCount($user),
        ]);
    }

    private function unreadNotificationsCount(User $user): int
    {
        return $user->unreadNotifications()
            ->where('created_at', '<=', Carbon::now())
            ->count();
    }

    public function favoriteToggle(Request $request)
    {
        $request->validate([
            'slug' => 'required|exists:posts,slug',
        ]);

        $post = Post::select('id')->where('slug', $request->slug)->firstOrFail();

        $user = auth('sanctum')->user();

        if ($user->favorites()->where('post_id', $post->id)->exists()) {
            $user->favorites()->detach($post->id);
        } else {
            $user->favorites()->attach($post->id);
        }

        return response()->json([
            'ok' => true,
        ]);
    }

    public function favorite(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $user = auth('sanctum')->user();

        $query = $user->favorites()
            ->select('posts.id', 'posts.title', 'posts.slug', 'posts.created_at')
            ->where('posts.status', 1)
            ->where('posts.created_at', '<', Carbon::now());

        $query->selectRaw("(SELECT 1 FROM post_favorite WHERE post_favorite.user_id = {$user->id} AND post_favorite.post_id = posts.id) as is_favorite");

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
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function settings(Request $request)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user, 403);

        $rules = [
            'name' => 'required',
            'phone' => 'required',
        ];

        if ($user->isUser()) {
            $rules['city_id'] = 'required';
            $rules['lastname'] = 'required';
            $rules['media_name'] = 'required';
        } else {
            $rules['user_category_id'] = 'required';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->description = $request->description;
        if ($request->lastname) {
            $user->lastname = $request->lastname;
        }
        if ($request->media_name) {
            $user->media_name = $request->media_name;
        }
        $user->newsletter = $request->newsletter? 1: 0;
        // dd($request->newsletter? 1: 0);
        $user->avatar = $request->avatar;
        $user->phone = $request->phone;
        if ($request->city_id) {
            $user->city_id = $request->city_id;
        }
        if ($request->user_category_id) {
            $user->user_category_id = $request->user_category_id;
        }
        $user->update();

        UserImageJob::dispatch($user);

        return response()->json([
            'status' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function email(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'email' => 'required|email|unique:users,email'
        ]);

        $user = auth('sanctum')->user();
        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => trans("Incorrect password"),
            ], 401);
        }

        $user->email = $request->email;
        $user->update();

        return response()->json([
            'status' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $user = User::where('email_verify_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'ok' => false,
                'message' => __('Invalid verification link'),
            ]);
        } else if ($user->email_verified_at) {
            return response()->json([
                'ok' => false,
                'message' => __('Email already verified'),
            ]);
        }

        $user->email_verified_at = Carbon::now();
        $user->update();

        return response()->json([
            'ok' => true,
            'message' => __('Email successfully verified'),
        ]);
    }

    public function updateAppToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'platform' => 'required',
        ]);

        $user = auth('sanctum')->user();
        abort_if(!$user, 403);

        $token = $user->currentAccessToken();

        if ($token) {
            $token->app_token = $request->token;
            $token->platform = $request->platform;
            $token->update();
        }

        return response()->json([
            'ok' => true,
        ]);
    }

    public function resendVerificationLink(Request $request)
    {
        $user = auth('sanctum')->user();
        abort_if(!$user, 403);

        if (!$user->email_verify_token) {
            $user->email_verify_token = \Str::uuid()->toString();
            $user->update();
        }

        $user->notify(new VerifyEmail($user));

        return response()->json([
            'ok' => true,
            'message' => __('Confirmation link sent to email'),
        ]);
    }

    public function password(Request $request)
    {
        $request->validate([
            'currentpassword' => 'required',
            'newpassword' => 'required',
        ]);

        $user = auth('sanctum')->user();

        if (!\Hash::check($request->currentpassword, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => trans("Incorrect password"),
            ], 401);
        }

        $user->password = \Hash::make($request->newpassword);
        $user->update();

        return response()->json([
            'status' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function delete(Request $request)
    {
        $user = auth('sanctum')->user();

        $user->name = $request->name;
        $user->image_id = $request->image;
        $user->newsletter = $request->newsletter? 1: 0;
        $user->update();

        return response()->json([
            'status' => true,
            'user' => $user->withInfo(),
        ]);
    }
}
