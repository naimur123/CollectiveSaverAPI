<?php

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
   Route::post('/create_group',[GroupController::class, 'create_group']);

});
