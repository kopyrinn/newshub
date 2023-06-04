<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v2\AccountController;
use App\Http\Controllers\Api\v2\AuthController as V2AuthController;
use App\Http\Controllers\Api\v2\CategoryController;
use App\Http\Controllers\Api\v2\PollController;
use App\Http\Controllers\Api\v2\PostController;
use App\Http\Controllers\Api\v2\SystemController;
use App\Http\Controllers\Api\v2\UploadController;
use App\Http\Controllers\Api\v2\UserController;
use App\Http\Controllers\Api\v2\VacancyController;

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

Route::prefix('v2')->group(function () {
    Route::get('user', [V2AuthController::class, 'user']);
    Route::get('fields', [V2AuthController::class, 'fields']);
    Route::post('forgot', [V2AuthController::class, 'forgot']);
    Route::post('reset', [V2AuthController::class, 'reset']);
    Route::post('telegram', [V2AuthController::class, 'telegram']);
    Route::post('telegram-connect', [V2AuthController::class, 'telegramConnect']);
    Route::post('telegram-disconnect', [V2AuthController::class, 'telegramDisconnect']);
    Route::post('login', [V2AuthController::class, 'login']);
    Route::post('register', [V2AuthController::class, 'register']);
    Route::post('delete-profile', [V2AuthController::class, 'deleteProfile'])->middleware('auth:sanctum');

    Route::get('tags/{tag}', [CategoryController::class, 'tag'])->name('tags');
    Route::post('category', [CategoryController::class, 'category'])->name('category');
    // Route::get('category/{slug}/{rubricSlug}', [CategoryController::class, 'rubric'])->name('category.rubric');

    Route::get('post/translate/{locale}/{slug}', [PostController::class, 'postTranslate'])->name('post.translate');
    Route::get('post/prev/{slug}', [PostController::class, 'postPrev'])->name('post.prev');
    Route::get('amp/post/{slug}', [PostController::class, 'postAmp'])->name('post.amp');
    Route::get('post/{slug}', [PostController::class, 'post'])->name('post');
    Route::post('post/editor', [PostController::class, 'editor'])->name('post.editor');
    Route::get('post/{slug}/{name}', [PostController::class, 'file'])->name('file');
    Route::post('post/grammar', [PostController::class, 'postGrammar'])->name('post.grammar');
    Route::get('more-posts/{offset}', [PostController::class, 'morePosts'])->name('post.more');
    Route::get('search', [PostController::class, 'search'])->name('search');
    Route::get('feed', [PostController::class, 'feed'])->name('feed');
    Route::get('new/{step?}/{id?}', [PostController::class, 'new'])->name('new');
    Route::post('post/save', [PostController::class, 'save'])->name('post.save');
    Route::post('post/update', [PostController::class, 'update'])->name('post.update');
    Route::post('draft-save', [PostController::class, 'draftSave'])->name('draft.save');
    Route::get('resolve/post/{uuid}', [PostController::class, 'postResolve'])->name('post.resolve');

    Route::get('vacancies', [VacancyController::class, 'vacancies'])->name('vacancies');
    Route::get('vacancy/{id}', [VacancyController::class, 'vacancy'])->name('vacancy');
    Route::post('vacancy/save', [VacancyController::class, 'save'])->name('vacancy.save');

    Route::get('users', [UserController::class, 'users'])->name('users');
    Route::get('users/{slug}', [UserController::class, 'category'])->name('users.category');
    Route::get('user/{id}', [UserController::class, 'user'])->name('user');
    Route::get('user/{id}/posts', [UserController::class, 'posts'])->name('user.posts');
    Route::get('user/{id}/actions', [UserController::class, 'actions'])->name('user.actions');
    Route::get('user/{id}/workspace', [UserController::class, 'workspace'])->name('user.workspace');
    Route::get('user/{id}/workspace/delete/{slug}', [UserController::class, 'postDelete'])->name('user.workspace.delete');
    Route::post('user/{id}/follow', [UserController::class, 'follow'])->name('user.follow');

    Route::get('account/notifications', [AccountController::class, 'notifications'])->name('account.notifications');
    Route::get('account/subscriptions', [AccountController::class, 'subscriptions'])->name('account.subscriptions');
    Route::post('account/settings', [AccountController::class, 'settings'])->name('account.settings');
    Route::post('account/email', [AccountController::class, 'email'])->name('account.email');
    Route::post('account/verify-resend', [AccountController::class, 'resendVerificationLink'])->name('account.verify.resend');
    Route::post('account/verify', [AccountController::class, 'verify'])->name('account.verify');
    Route::post('account/password', [AccountController::class, 'password'])->name('account.password');
    Route::post('account/delete', [AccountController::class, 'delete'])->name('account.delete');

    Route::get('polls', [PollController::class, 'polls'])->name('polls');
    Route::get('polls/{slug}', [PollController::class, 'poll'])->name('polls.view');
    Route::post('polls/{slug}/request', [PollController::class, 'pollRequest'])->name('polls.request');
    Route::post('polls/{slug}/vote', [PollController::class, 'pollVote'])->name('polls.vote');

    Route::get('config', [SystemController::class, 'config'])->name('config');
    Route::get('money-list', [SystemController::class, 'getMoneyList'])->name('getMoneyList');
    Route::get('packages', [SystemController::class, 'packages'])->name('packages');
    Route::get('package/{slug}', [SystemController::class, 'packagesPay'])->name('packages.pay');
    Route::post('package/{slug}', [SystemController::class, 'packagesPay']);
    Route::get('page/{slug}', [SystemController::class, 'page'])->name('page');
    Route::get('map', [SystemController::class, 'map'])->name('map');
    Route::get('journalists', [SystemController::class, 'journalists'])->name('journalists');

    Route::post('image/{figure}', [UploadController::class, 'image'])->name('upload.image');
});

Route::prefix('v1')->group(function () {
    Route::group([
        'middleware' => 'api'
    ], function ($router) {
        Route::post('login', [AuthController::class, 'authenticate']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('token/validate', [AuthController::class, 'verify']);
        
        
    });

    Route::group([
        'middleware' => 'auth:api'
    ], function ($router) {
        Route::group(['middleware' => ['jwt.verify']], function() {
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('resend', [AuthController::class, 'resend']);
            Route::get('me', [AuthController::class, 'me']);
            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('follow/{id}', [HomeController::class, 'userFollow'])->name('api.follow');
            Route::get('unfollow/{id}', [HomeController::class, 'userUnfollow'])->name('api.unfollow');
            Route::get('feed', [HomeController::class, 'feed'])->name('api.feed');
        });
    });

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('posts/{id?}', [HomeController::class, 'posts'])->name('api.posts');
    Route::get('popular-posts', [HomeController::class, 'popularPosts'])->name('api.posts.popular');
    Route::get('categories', [HomeController::class, 'categories'])->name('api.categories');
    Route::get('vacancies', [HomeController::class, 'vacancies'])->name('api.vacancies');
    Route::get('users/{role?}', [HomeController::class, 'users'])->name('api.users');
    Route::post('regions', [HomeController::class, 'regions'])->name('api.regions');
    Route::post('cities', [HomeController::class, 'cities'])->name('api.cities');
});