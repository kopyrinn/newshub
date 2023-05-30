<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
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

        $query = $user->notifications()->where('created_at', '<=', Carbon::now());

        $notifications = $query
            ->paginate($request->per_page?: 15)
            ->withQueryString();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'ok' => true,
            'notifications' => $notifications,
        ]);
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
        } else {
            $rules['user_category_id'] = 'required';
        }

        $request->validate($rules);

        $user->name = $request->name;
        if ($request->lastname) {
            $user->lastname = $request->lastname;
        }
        $user->avatar = $request->avatar;
        $user->phone = $request->phone;
        if ($request->city_id) {
            $user->city_id = $request->city_id;
        }
        if ($request->user_category_id) {
            $user->user_category_id = $request->user_category_id;
        }
        $user->update();

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
