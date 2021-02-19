<?php

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

Route::get('/', function () {
    return view('auth.login');
})->middleware('rolehome');

Auth::routes(['register' => false]);

/* Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); */

Route::resource('/admin', App\Http\Controllers\AdminController::class)->middleware('isadmin');
Route::resource('/tech', App\Http\Controllers\TechController::class)->middleware('istech');
Route::resource('/user', App\Http\Controllers\UserController::class)->middleware('isuser');
