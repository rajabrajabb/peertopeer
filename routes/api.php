<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// authinticate
Route::post('/register',[UserController::class,'register']);
Route::post('/login', [UserController::class,'login']);

//User
Route::prefix('user')->middleware('auth:sanctum')->group(function(){
    Route::put('/update',[UserController::class,'update']);
    Route::get('/{user}',[UserController::class,'show']);
});

Route::resource('services', ServiceController::class)->middleware('auth:sanctum');
Route::resource('types', TypeController::class);
Route::resource('categories', CategoryController::class);

Route::post('/add_to_favorit',[ServiceController::class,'addServiceTofavorit'])->middleware('auth:sanctum');
Route::post('/remove_from_favorit',[ServiceController::class,'deleteServiceFromFavorite'])->middleware('auth:sanctum');
Route::post('/add_search_value/{service}',[ServiceController::class,'addSearchValue']);

Route::get('/favorit_services',[UserController::class,'favoriteServices'])->middleware('auth:sanctum');
Route::get('/user_services',[UserController::class,'userServices'])->middleware('auth:sanctum');
