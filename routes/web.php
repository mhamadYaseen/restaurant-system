<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;


Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::resource('items', ItemController::class);
Route::resource('orders', OrderController::class);  
Route::resource('categories', CategoryController::class);