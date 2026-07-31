<?php

use App\Http\Controllers\AdReportController;
use App\Http\Controllers\Api\v2\SystemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// PDF-отчёт по баннеру за конкретный день (доступен только авторизованным)
Route::get('report/ad-day/{ad}/{date}', [AdReportController::class, 'day'])
    ->middleware('auth')
    ->where('date', '\d{4}-\d{2}-\d{2}')
    ->name('report.ad.day');

Route::get('report/ad-period/{ad}', [AdReportController::class, 'period'])
    ->middleware('auth')
    ->name('report.ad.period');

Auth::routes(['verify' => true]);
