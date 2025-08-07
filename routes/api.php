<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;

Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);


