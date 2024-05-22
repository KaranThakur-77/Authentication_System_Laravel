<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return view('welcome');
});
Route::group(['prefix' => 'account'], function(){
    
    // Middleware for guest users.
    Route::group(['middleware' => 'guest'],function(){
        
        Route::get('login',[LoginController::class,'index'])->name('account.login');
        Route::post('authenticate',[LoginController::class,'authenticate'])->name('account.authenticate');
        Route::get('register',[RegisterController::class,'index'])->name('account.register');
        Route::post('process-register',[RegisterController::class,'processRegister'])->name('account.processRegister');

    });

    // Middleware for authenticated users.
    Route::group(['middleware' => 'auth'],function(){
            Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');
            Route::get('logout',[LoginController::class,'logout'])->name('account.logout');
        });
});

