<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Content\FaqController;
use App\Http\Controllers\Content\HomeController;
use App\Http\Controllers\Content\PostController;
use App\Http\Controllers\Content\ProductCategoryController;
use App\Http\Controllers\Content\ProductController;
use App\Http\Controllers\Content\ShopController;
use Illuminate\Support\Facades\Route;

Route::get("/account", [HomeController::class, "index"])->name("account.index");
Route::get("/checkout", [CheckoutController::class, "index"])->name(
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
Route::get("product-category/{slug}", [
    ProductCategoryController::class,
    "index",
])->name("product-category.index");

Route::get("/about", [HomeController::class, "index"])->name("about.index");
Route::get("/terms-conditions", [HomeController::class, "index"])->name(
    "terms.index"
);
Route::get("/privacy-policy", [HomeController::class, "index"])->name(
    "privacy.index"
);

Route::get("/faq", [FaqController::class, "index"])->name("faq.index");
Route::get("/", [HomeController::class, "index"])->name("storefront.index");
Route::get("/shop", [ShopController::class, "index"])->name("shop.index");
Route::get("/product/{product:slug}", [ProductController::class, "show"])->name(
    "product.show"
);
Route::controller(PostController::class)->group(function () {
    Route::get("/post/{post:slug}", "show")->name("post.show");
    Route::get("/blog", "index")->name("post.index");
    Route::get("/blog/preview/{id}", "preview")->name("post.preview");
});

Route::get("/collection/{bundle:slug}", [
    ProductController::class,
    "showBundle",
])->name("product.bundle");
