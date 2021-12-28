<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

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

/** root uri pointed to reservation.create this has to be replaced for the landing page if any */
Route::get('/', [ReservationController::class, 'create']);

/** reservation routes */
Route::get('reservation/create/second', [ReservationController::class, 'createSecondStep'])->name('reservation.create.second');
Route::post('reservation/first', [ReservationController::class, 'processFirstStep'])->name('reservation.store.first');
Route::resource('reservation', ReservationController::class)->except('show');

/** user routes */
Route::get('user/login', [UserController::class, 'loginShow'])->middleware('guest')->name('user.login.show');
Route::post('user/login', [UserController::class, 'login'])->middleware('guest')->name('user.login');
Route::get('user/logout', [UserController::class, 'logout'])->middleware('auth')->name('user.logout');
Route::put('user/{user}/password', [UserController::class, 'updatePassword'])->middleware('auth')->name('user.update.password');
Route::get('user/{user}/reservations', [UserController::class, 'showReservations'])->middleware('auth')->name('user.reservations.show');
Route::resource('user', UserController::class)->except('show');

/** session routes */
Route::resource('session', SessionController::class)->except('create','show',)->middleware('admin');
