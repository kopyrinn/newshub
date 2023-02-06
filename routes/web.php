<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MobileHomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FcmController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('m.newshub.kz')->group(function () {
    Route::get('/', [MobileHomeController::class, 'index'])->name('mobile.home');
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function() {
    Route::middleware(['approved'])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::patch('/fcm-token', [FcmController::class, 'token'])->name('fcm.token');

        Route::get('/money-list', [HomeController::class, 'getMoneyList'])->name('getMoneyList');

        Route::get('search', [HomeController::class, 'search'])->name('search');
        Route::get('users', [HomeController::class, 'users'])->name('users');
        Route::get('users/{slug}', [HomeController::class, 'usersCategory'])->name('users.category');
        Route::get('user/{id}', [HomeController::class, 'user'])->name('user');
        Route::get('packages', [HomeController::class, 'packages'])->name('packages');
        Route::get('vacancies', [HomeController::class, 'vacancies'])->name('vacancies');
        Route::get('vacancy/{id}', [HomeController::class, 'vacancy'])->name('vacancy');
        Route::get('page/{slug}', [HomeController::class, 'page'])->name('page');
        Route::get('tags/{tag}', [HomeController::class, 'tag'])->name('tags');
        Route::get('category/{slug}', [HomeController::class, 'category'])->name('category');
        Route::get('category/{slug}/{rubricSlug}', [HomeController::class, 'categoryRubric'])->name('category.rubric');
        Route::get('post/translate/{locale}/{slug}', [HomeController::class, 'postTranslate'])->name('post.translate');
        Route::get('post/prev/{slug}', [HomeController::class, 'postPrev'])->name('post.prev');
        Route::get('amp/post/{slug}', [HomeController::class, 'postAmp'])->name('post.amp');
        Route::get('post/{slug}', [HomeController::class, 'post'])->name('post');
        Route::get('post/{slug}/{name}', [HomeController::class, 'file'])->name('file');
        Route::get('map', [HomeController::class, 'map'])->name('map');
        Route::get('journalists', [HomeController::class, 'journalists'])->name('journalists');
        Route::get('more-posts/{offset}', [HomeController::class, 'morePosts'])->name('post.more');
        Route::get('polls', [HomeController::class, 'polls'])->name('polls');
        Route::get('polls/{slug}', [HomeController::class, 'poll'])->name('polls.view');
        Route::post('polls/{slug}/request', [ProfileController::class, 'pollRequest'])->name('polls.request');
        Route::post('post/grammar', [HomeController::class, 'postGrammar'])->name('post.grammar');

        Route::middleware(['verified'])->group(function () {
            Route::get('user/{id}/follow', [ProfileController::class, 'userFollow'])->name('user.follow');
            Route::get('user/{id}/unfollow', [ProfileController::class, 'userUnfollow'])->name('user.unfollow');
            Route::post('polls/{slug}/vote', [ProfileController::class, 'pollVote'])->name('polls.vote');
            Route::get('package/{slug}', [ProfileController::class, 'packagesPay'])->name('packages.pay');
            Route::post('package/{slug}', [ProfileController::class, 'packagesPay']);
            Route::get('feed', [ProfileController::class, 'feed'])->name('feed');
            Route::get('new/{step?}/{id?}', [ProfileController::class, 'new'])->name('new');
            Route::get('new-vacancy', [ProfileController::class, 'newVacancy'])->name('new.vacancy');
            Route::post('new', [ProfileController::class, 'newSave'])->name('new.save');
            Route::post('draft-save', [ProfileController::class, 'draftSave'])->name('draft.save');
            Route::post('new-vacancy', [ProfileController::class, 'newVacancySave'])->name('new.vacancy.save');
            Route::get('workspace', [ProfileController::class, 'workspace'])->name('workspace');
            Route::get('actions', [ProfileController::class, 'actions'])->name('actions');
            Route::get('notifications', [ProfileController::class, 'notifications'])->name('notifications');
            Route::get('workspace/post/{slug}', [ProfileController::class, 'workspacePost'])->name('workspace.post');
            Route::post('workspace/post/{slug}', [ProfileController::class, 'workspacePost']);
            Route::post('upload/image', [UploadController::class, 'image'])->name('upload.image');
            Route::get('workspace/delete/{slug}', [ProfileController::class, 'workspaceDelete'])->name('workspace.delete');
            Route::get('resolve/post/{uuid}', [ProfileController::class, 'postResolve'])->name('post.resolve');
        });
    });
    

    Route::get('register/role', [ProfileController::class, 'registerRole'])->name('register.role');
    Route::post('register/role', [ProfileController::class, 'registerRole']);
    Route::get('register/place', [ProfileController::class, 'registerPlace'])->name('register.place');
    Route::post('register/place', [ProfileController::class, 'registerPlace']);

    Route::get('settings', [ProfileController::class, 'settings'])->name('settings');
    Route::post('settings/basic', [ProfileController::class, 'settingsBasic'])->name('settingsBasic');
    Route::post('settings/contacts', [ProfileController::class, 'settingsContacts'])->name('settingsContacts');
    Route::post('settings/access', [ProfileController::class, 'settingsAccess'])->name('settingsAccess');
    Route::post('settings/upload', [ProfileController::class, 'settingsUpload'])->name('settingsUpload');
    
    Route::get('rss', 'App\Http\Controllers\RssFeedController@feed');
    Route::get('turbo', 'App\Http\Controllers\RssFeedController@turbo');

    Route::get('goto/{id}', [HomeController::class, 'goto'])->name('goto');

    Route::get('/profile', function () {return redirect('/new');});

    Auth::routes(['verify' => true]);
});

Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth', 'approved']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
