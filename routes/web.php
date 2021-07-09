<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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

Route::get('/', static function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

Route::group([
    'middleware' => 'auth',
    //'prefix' => 'payment',
    'as'         => 'payment.',
], static function () {
    Route::get('new-payment', [PaymentController::class, 'create'])->name('create');
    Route::post('payment', [PaymentController::class, 'store'])->name('store');
});
