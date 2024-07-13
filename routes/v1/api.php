<?php

use App\Http\Controllers\v1\FundController;
use App\Http\Controllers\v1\GroupController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[UserController::class, 'testApiResult']);
Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);

Route::middleware(["auth:sanctum"])->group(function(){
   Route::post('/logout',[UserController::class, 'logout']);


   /* Group Create Edit Delete */
   Route::get('/groups/{id}',[GroupController::class, 'index']);
   Route::get('/groups',[GroupController::class, 'index']);
   Route::post('/create_group',[GroupController::class, 'store_group']);
   Route::post('/create_group/{id}',[GroupController::class, 'store_group']);
   Route::post('/delete_group/{id}',[GroupController::class, 'delete_group']);

   /* Fund Create Edit Delete */
   Route::post('/store_fund',[FundController::class, 'store_fund']);
});
