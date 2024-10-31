<?php

use App\Http\Controllers\StoreFront\HomeController;
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, "index"])->name("storefront.index");
Route::get("/shop", [HomeController::class, "index"])->name("shop.index");
Route::get("/account", [HomeController::class, "index"])->name("account.index");
Route::get("/checkout", [HomeController::class, "index"])->name(
    "checkout.index"
);
Route::get("/order-tracking", [HomeController::class, "index"])->name(
    "order.tracking"
);

Route::get("/contact-us", [HomeController::class, "index"])->name(
    "contact.index"
);
Route::get("/blog", [HomeController::class, "index"])->name("blog.index");
Route::get("/refund_returns", [HomeController::class, "index"])->name(
    "return.index"
);
Route::get("/faq", [HomeController::class, "index"])->name("faq.index");

Route::get("/about", [HomeController::class, "index"])->name("about.index");
Route::get("/terms-conditions", [HomeController::class, "index"])->name(
    "terms.index"
);
Route::get("/privacy-policy", [HomeController::class, "index"])->name(
    "privacy.index"
);
