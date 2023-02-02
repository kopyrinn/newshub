<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * User registration
     */
    public function register(Request $request)
    {
        $params = [
            'phone' => 'required|string',
            'name' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
        ];

        if ($request->role == 'journalist') {
            $params['lastname'] = 'string';
        }

        $request->validate($params);

        $user = User::create([
            'phone' => $request->phone,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city_id' => $request->city_id,
        ]);

        if ($request->role == 'journalist') {
            $user->lastname = $request->lastname;
            $user->update();
        }

        if (in_array($request->role, ['reader', 'journalist'])) {
            $user->assignRole($request->role);
        } else {
            $user->assignRole('journalist');
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => __('Done.'),
            'data' => $user
        ]);
    }

    /**
     * Resend email verification
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend()
    {
        if (auth('api')->user()->hasVerifiedEmail()) {
            return response()->json(["message" => __("Email already verified.")]);
        }

        auth('api')->user()->sendEmailVerificationNotification();

        return response()->json(["message" => __("Email verification link sent on your email")]);
    }

    
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = request(['username', 'password']);
        $credentials['email'] = $credentials['username'];
        unset($credentials['username']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();
        $user->addAction('login');

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => __('Done.')]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function verify(Request $request)
    {
        return response()->json([
            'code' => !auth('api')->guest()? 'jwt_auth_valid_token': 'jwt_auth_invalid_token',
            'data' => [
                'status' => !auth('api')->guest()? 200: 403
            ]
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $user = auth('api')->user();
        $user->avatar = asset("storage/{$user->avatar}");

        return response()->json($user);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_email' => $user->email,
            'user_nicename' => $user->getName(),
            'user_display_name' => $user->getName(),
        ]);
    }
}
