<?php

use App\Http\Controllers\StoreFront\HomeController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "index"])->name("storefront.home");
