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

Route::get('user/login',[UserController::class,'loginShow'])->name('user.auth.login.show');
Route::post('user/login',[UserController::class,'login'])->name('user.auth.login.make');
Route::get('user/register',[UserController::class,'registerShow'])->name('user.auth.register.show');
Route::post('user/register',[UserController::class,'register'])->name('user.auth.register.make');
Route::view('user/profile','users.profile')->name('user.auth.profile.show')->middleware('auth');
Route::put('user/profile/{user}',[UserController::class,'updateProfile'])->name('user.auth.profile.make')->middleware('auth');
Route::put('user/password/{user}',[UserController::class,'updatePassword'])->name('user.auth.password.make')->middleware('auth');
Route::delete('user/delete/{user}',[UserController::class,'destroy'])->name('user.auth.profile.delete')->middleware('auth');
Route::get('user/logout',[UserController::class,'logout'])->name('user.auth.logout.make')->middleware('auth');
//Route::resource('user',UserController::class);
