<?php

use App\UserInterface\Sales\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource("product", ProductController::class);
