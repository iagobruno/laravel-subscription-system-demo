<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SubscriptionController;
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

Route::view('/', 'landing')->name('landing');

Route::get('/login', [AuthController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'handle'])->middleware('guest')->name('login.post');
Route::post('/logout', [LogoutController::class, 'handle'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/subscribe', [SubscriptionController::class, 'show'])->name('subscribe');
    Route::post('/subscribe', [SubscriptionController::class, 'handle'])->name('subscribe.post');
});
