<?php

namespace App\Http\Controllers\Api\v2;

use App\Helpers\Upload;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Azate\LaravelTelegramLoginAuth\TelegramLoginAuth;

class AuthController extends Controller
{
    public function user()
    {
        $user = auth('sanctum')->user();

        return response()->json([
            'ok' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function telegram(TelegramLoginAuth $telegramLoginAuth, Request $request)
    {
        if ($tgUser = $telegramLoginAuth->validate($request)) {
            $user = User::whereTelegramId($tgUser->getId())->first();

            if (!$user) {
                $user = new User;
                $user->email = strtolower(\Str::random(16)) . '@' . config('app.domain');
                $user->telegram_id = $tgUser->getId();
                $user->telegram_username = $tgUser->getUsername();
                $user->name = implode(" ", [$tgUser->getFirstName(), $tgUser->getLastName()]);
                $user->save();
                $user->refresh();

                if ($tgUser->getPhotoUrl()) {
                    $avatar = Upload::byUrl($tgUser->getPhotoUrl(), $user->id);
                    if ($avatar) {
                        $user->image_id = $avatar->id;
                        $user->update();
                    }
                }
            }

            $token = $user->createToken($request->ip() . ':' . $request->userAgent());

            $user->addAction('login');

            return response()->json([
                'ok' => true,
                'user' => $user->withInfo(),
                'token' => $token->plainTextToken
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => [
                    'email' => ['Ошибка сервера, пожалуйста, попробуйте другой способ авторизации.']
                ]
            ]);
        }
    }

    public function telegramConnect(TelegramLoginAuth $telegramLoginAuth, Request $request)
    {
        $user = auth('sanctum')->user();

        if ($tgUser = $telegramLoginAuth->validate($request)) {
            if (User::whereTelegramId($tgUser->getId())->exists()) {
                return response()->json([
                    'ok' => false,
                    'title' => 'Ошибка подключения',
                    'message' => 'Telegram аккаунт уже привязан к другой учетной записи'
                ]);
            }

            $user->telegram_id = $tgUser->getId();
            $user->telegram_username = $tgUser->getUsername();
            $user->name = implode(" ", [$tgUser->getFirstName(), $tgUser->getLastName()]);

            $avatar = Upload::byUrl($tgUser->getPhotoUrl(), $user->id);
            if ($avatar) {
                $user->image_id = $avatar->id;
            }

            $user->update();

            return response()->json([
                'ok' => true,
                'user' => $user->withInfo(),
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'title' => 'Ошибка подключения'
            ]);
        }
    }

    public function telegramDisconnect(Request $request)
    {
        $user = auth('sanctum')->user();

        $request->validate([
            'url' => 'required',
        ]);

        $user->telegram_username = null;
        $user->telegram_id = null;
        $user->update();

        return response()->json([
            'ok' => true,
            'user' => $user->withInfo(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = User::whereEmail($request->email)->first();
            $token = $user->createToken($request->ip() . ':' . $request->userAgent());

            $user->addAction('login');

            return response()->json([
                'ok' => true,
                'user' => $user->withInfo(),
                'token' => $token->plainTextToken
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'errors' => [
                    'email' => ['Неверный Email или пароль']
                ]
            ]);
        }
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        $ok = $status === Password::RESET_LINK_SENT;

        if ($ok) {
            return response()->json([
                'ok' => $ok,
                'message' => __($status)
            ]);
        }

        return response()->json([
            'ok' => $ok,
            'message' => __($status),
            'errors' => ['email' => [__($status)]]
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Hash::make($password)
                ])->setRememberToken(\Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        $ok = $status === Password::PASSWORD_RESET;

        if ($ok) {
            return response()->json([
                'ok' => $ok,
                'message' => __($status)
            ]);
        }

        return response()->json([
            'ok' => $ok,
            'message' => __($status),
            'errors' => ['email' => [__($status)]]
        ]);
    }

    public function deleteProfile(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'ok' => false,
            ]);
        }

        $user->delete();

        return response()->json([
            'ok' => true,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'password' => 'required|string|confirmed|min:6|max:50',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->refresh();

        // if (!$user->is_active) {
        //     Helper::notify("Новая заявка на регистрацию от пользвоателя {$user->name}");
        // }

        if (in_array($request->role, ['press', 'journalist'])) {
            $user->assignRole($request->role);
        } else {
            $user->assignRole('journalist');
        }

        $token = $user->createToken($request->ip() . ':' . $request->userAgent());

        $user->sendEmailVerificationNotification();

        return response()->json([
            'ok' => true,
            'user' => $user->withInfo(),
            'token' => $token->plainTextToken
        ]);
    }
}
