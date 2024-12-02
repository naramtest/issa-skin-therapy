<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\Content\FaqController;
use App\Http\Controllers\Content\HomeController;
use App\Http\Controllers\Content\PostController;
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

Route::get("/blog", [HomeController::class, "index"])->name("blog.index");
Route::get("/refund_returns", [HomeController::class, "index"])->name(
    "return.index"
);

Route::get("/terms-conditions", [HomeController::class, "index"])->name(
    "terms.index"
);
Route::get("/privacy-policy", [HomeController::class, "index"])->name(
    "privacy.index"
);

Route::get("/faq", [FaqController::class, "index"])->name("faq.index");
Route::get("/", [HomeController::class, "index"])->name("storefront.index");

Route::controller(ShopController::class)->group(function () {
    Route::get("/shop", "index")->name("shop.index");
    Route::get("/collections-page", "collection")->name("bundles.index");
});

Route::controller(PostController::class)->group(function () {
    Route::get("/post/{post:slug}", "show")->name("posts.show");
    Route::get("/blog", "index")->name("posts.index");
    Route::get("/blog/preview/{id}", "preview")->name("post.preview");
});
Route::controller(ProductController::class)->group(function () {
    Route::get("/product/{product:slug}", "show")->name("product.show");
    Route::get("/collection/{bundle:slug}", "showBundle")->name(
        "product.bundle"
    );
    Route::get("product-category/{slug}", "showProductCategory")->name(
        "product.category"
    );
});

Route::get("/about", [AboutController::class, "index"])->name("about.index");
Route::get("/contact-us", [ContactUsController::class, "index"])->name(
    "contact.index"
);
