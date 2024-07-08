<?php

use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/test',[UserController::class, 'testApiResult']);
Route::post('/register',[UserController::class, 'register']);
