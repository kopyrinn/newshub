<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Approved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->guest()) {
            $user = auth()->user();

            if (!$user->roles()->exists()) {
                return Redirect::route('register.role');
            } else if ($user->isUser() && (!$user->city_id || !$user->name)) {
                return Redirect::route('register.place');
            } else if ($user->isPress() && (!$user->name || !$user->description || !$user->user_category_id)) {
                return Redirect::route('settings');
            }
        }

        return $next($request);
    }
}
