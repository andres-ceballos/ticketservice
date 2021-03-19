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
Route::delete('/deleteSelectUsers', [App\Http\Controllers\AdminController::class, 'destroyAll'])->middleware('isadmin');

Route::resource('/tech', App\Http\Controllers\TechController::class)->middleware('istech');
Route::resource('/user', App\Http\Controllers\UserController::class)->middleware('isuser');
Route::resource('/service-rating', App\Http\Controllers\UserRatingController::class);

Route::resource('/incident', App\Http\Controllers\IncidentController::class);
Route::resource('/detail-incident', App\Http\Controllers\DetailIncidentController::class);
