<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MobileHomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FcmController;
use Illuminate\Support\Facades\App;

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
    'domain' => config('app.domain')
], function () {
    if (config('app.env') == 'local') {
        Route::get('iam/dev', function() {
            return redirect('/')->withCookie(cookie()->forever('is_dev', '1'));
        });
    }

    Route::get('{capture?}', function () {
        if (in_array(request()->segment(1), ['en', 'kk'])) {
            App::setLocale(request()->segment(1));
        } else {
            App::setLocale('ru');
        }

        return view('web');
    })->where('capture', '(?!admin|nova-api|nova-vendor).*');
});

// Route::middleware(['approved'])->group(function () {
//     Route::get('/', [HomeController::class, 'index'])->name('home');
//     Route::patch('/fcm-token', [FcmController::class, 'token'])->name('fcm.token');

//     Route::middleware(['verified'])->group(function () {
//     });
// });

// Route::get('register/role', [ProfileController::class, 'registerRole'])->name('register.role');
// Route::post('register/role', [ProfileController::class, 'registerRole']);
// Route::get('register/place', [ProfileController::class, 'registerPlace'])->name('register.place');
// Route::post('register/place', [ProfileController::class, 'registerPlace']);

// Route::get('settings', [ProfileController::class, 'settings'])->name('settings');
// Route::get('account', [ProfileController::class, 'account'])->name('account');
// Route::post('settings/basic', [ProfileController::class, 'settingsBasic'])->name('settingsBasic');
// Route::post('settings/access', [ProfileController::class, 'settingsAccess'])->name('settingsAccess');
// Route::post('settings/upload', [ProfileController::class, 'settingsUpload'])->name('settingsUpload');

Route::get('rss', 'App\Http\Controllers\RssFeedController@feed');
Route::get('turbo', 'App\Http\Controllers\RssFeedController@turbo');

// Route::get('goto/{id}', [HomeController::class, 'goto'])->name('goto');

// Route::get('/profile', function () {return redirect('/new');});

// Auth::routes(['verify' => true]);