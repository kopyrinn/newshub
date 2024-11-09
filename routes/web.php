<?php

use App\Http\Controllers\Api\v2\SystemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobileHomeController;
use Illuminate\Support\Facades\Auth;

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
    Route::get('rss', 'App\Http\Controllers\RssFeedController@feed');
    Route::get('turbo', 'App\Http\Controllers\RssFeedController@turbo');
});

Route::get('feed', [SystemController::class, 'feed'])->name('rss.feed');

Auth::routes(['verify' => true]);
