<?php

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
    Route::get('/', [MobileHomeController::class, 'index'])->name('mobile.home');
});

Auth::routes(['verify' => true]);