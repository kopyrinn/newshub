<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'name' => 'required',
        ]);

        $user = auth('sanctum')->user();

        // $isNew = !$user->org && $request->org? true: false;

        $user->name = $request->name;
        // if ($request->org) {
        //     $user->org = $request->org;
        // }
        // $user->image_id = (int) $request->avaId?: null;
        // $user->newsletter = $request->newsletter? 1: 0;
        $user->update();

        // if ($isNew && $user->org && !$user->is_active) {
        //     Helper::notify("Новая заявка на регистрацию от пользвоателя {$user->name}");
        // }

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
