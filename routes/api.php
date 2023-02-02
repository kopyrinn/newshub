<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('v1/login', [ApiController::class, 'authenticate']);
    Route::post('v1/register', [ApiController::class, 'register']);
    Route::post('v1/token/validate', [ApiController::class, 'verify']);
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::post('v1/refresh', [ApiController::class, 'refresh']);
        Route::get('v1/resend', [ApiController::class, 'resend']);
        Route::get('v1/me', [ApiController::class, 'me']);
        Route::get('v1/logout', [ApiController::class, 'logout']);
        Route::get('v1/follow/{id}', [HomeController::class, 'userFollow'])->name('api.follow');
        Route::get('v1/unfollow/{id}', [HomeController::class, 'userUnfollow'])->name('api.unfollow');
        Route::get('v1/feed', [HomeController::class, 'feed'])->name('api.feed');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v1/posts/{id?}', [HomeController::class, 'posts'])->name('api.posts');
Route::get('v1/popular-posts', [HomeController::class, 'popularPosts'])->name('api.posts.popular');
Route::get('v1/categories', [HomeController::class, 'categories'])->name('api.categories');
Route::get('v1/vacancies', [HomeController::class, 'vacancies'])->name('api.vacancies');
Route::get('v1/users/{role?}', [HomeController::class, 'users'])->name('api.users');
Route::post('v1/regions', [HomeController::class, 'regions'])->name('api.regions');
Route::post('v1/cities', [HomeController::class, 'cities'])->name('api.cities');
