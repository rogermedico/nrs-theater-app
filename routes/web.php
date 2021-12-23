<?php

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

Route::get('/', function () {
    return view('main');
});
Route::resource('reservation', ReservationController::class);

Route::get('user/login',[UserController::class,'loginShow'])->name('user.login.show')->middleware('guest');
Route::post('user/login',[UserController::class,'login'])->name('user.login')->middleware('guest');
Route::get('user/logout',[UserController::class,'logout'])->name('user.logout')->middleware('auth');
//Route::get('user/register',[UserController::class,'registerShow'])->name('user.auth.register.show');
//Route::post('user/register',[UserController::class,'register'])->name('user.auth.register.make');
//Route::view('user/profile','users.profile')->name('user.auth.profile.show')->middleware('auth');
Route::put('user/{user}/profile',[UserController::class,'updateProfile'])->name('user.update.profile')->middleware('auth');
Route::put('user/{user}/password',[UserController::class,'updatePassword'])->name('user.update.password')->middleware('auth');
//Route::delete('user/delete/{user}',[UserController::class,'destroy'])->name('user.auth.profile.delete')->middleware('auth');

Route::resource('user',UserController::class)->except('update');
