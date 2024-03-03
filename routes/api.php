<?php

use App\UserInterface\Sales\Controllers\OrderController;
use App\UserInterface\Sales\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource("product", ProductController::class)->only("index");
Route::resource("order", OrderController::class)->only("index", "store", "show", "destroy", "update");
