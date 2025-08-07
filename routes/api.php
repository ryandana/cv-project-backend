<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1️⃣ Quick test endpoint
Route::get('/ping', fn() => response()->json(['pong' => true]));

// 2️⃣ Blog endpoints
Route::get('/blogs',  [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);