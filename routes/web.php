<?php

use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticationController::class, 'create'])->name('login'); 
    Route::post('login', [AuthenticationController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');

    Route::get('/',[HomeController::class,'index'])->name('home');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('branches', BranchController::class);
    
});