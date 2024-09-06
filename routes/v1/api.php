<?php

use App\Http\Controllers\v1\FundController;
use App\Http\Controllers\v1\GroupController;
use App\Http\Controllers\v1\SystemController;
use App\Http\Controllers\v1\UserController;
use App\Http\Middleware\TokenExpiration;
use Illuminate\Support\Facades\Route;

Route::get('/test',[UserController::class, 'testApiResult']);
Route::get('/system',[SystemController::class, 'index']);
Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'token_expiration'])->group(function(){
   Route::post('/logout',[UserController::class, 'logout']);

   /* Get User */
   Route::get('/get_user/{id}',[UserController::class, 'index']);

   /* Add Audit trail or Activity */
   Route::post('/saveActivity',[UserController::class, 'saveActivity']);

   /* Group Create Edit Delete */
   Route::get('/groups/{id}',[GroupController::class, 'index']);
   Route::get('/groups',[GroupController::class, 'index']);
   Route::post('/create_group',[GroupController::class, 'store_group']);
   Route::post('/create_group/{id}',[GroupController::class, 'store_group']);
   Route::post('/delete_group/{id}',[GroupController::class, 'delete_group']);

   /* Fund Create Edit Delete */
   Route::post('/fund',[FundController::class, 'index']);
   Route::get('/user_group_fund',[FundController::class, 'user_group_fund']);
   Route::get('/user_fund',[FundController::class, 'user_fund']);
   Route::post('/store_fund',[FundController::class, 'store_fund']);

});
