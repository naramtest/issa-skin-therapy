<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartPrefillController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Content\AboutController;
use App\Http\Controllers\Content\ContactUsController;
use App\Http\Controllers\Content\FaqController;
use App\Http\Controllers\Content\HomeController;
use App\Http\Controllers\Content\LegalController;
use App\Http\Controllers\Content\PostController;
use App\Http\Controllers\Content\ProductController;
use App\Http\Controllers\Content\ShopController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Http\Controllers\Webhook\TabbyWebhookController;
use App\Services\UrlShortenerService;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        "prefix" => LaravelLocalization::setLocale(),
        "middleware" => [
            "localeSessionRedirect",
            "localizationRedirect",
            "localeViewPath",
        ],
    ],
    function () {
        Route::get("/faq", [FaqController::class, "index"])->name("faq.index");
        Route::get("/", [HomeController::class, "index"])->name(
            "storefront.index"
        );

        Route::controller(ShopController::class)->group(function () {
            Route::get("/shop", "index")->name("shop.index");
            Route::get("/collections-page", "collection")->name(
                "bundles.index"
            );
        });

        Route::controller(CartController::class)->group(function () {
            Route::get("/cart", "index")->name("cart.index");
        });

        Route::controller(CheckoutController::class)->group(function () {
            Route::get("/checkout", "index")->name("checkout.index");
            Route::get("/checkout/success", "success")->name(
                "checkout.success"
            );
            Route::get("/checkout/cancel", "cancel")->name("checkout.cancel");
            Route::get("/checkout/failure", "failure")->name(
                "checkout.failure"
            );
        });

        Route::get("/about", [AboutController::class, "index"])->name(
            "about.index"
        );
        Route::get("/contact-us", [ContactUsController::class, "index"])->name(
            "contact.index"
        );

        Route::controller(AuthController::class)
            ->middleware("guest")
            ->group(function () {
                Route::get("/login", "login")->name("login");
                Route::get("/register", "register")->name("register");
            });

        Route::controller(AccountController::class)
            ->prefix("/my-account")
            ->middleware(["verified"])
            ->group(function () {
                Route::get("/", "show")->name("account.index");
                Route::get("edit-account", "edit")->name("account.edit");
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

        Route::controller(LegalController::class)->group(function () {
            Route::get("/refund_returns", "refund")->name("return.index");
            Route::get("/terms-conditions", "terms")->name("terms.index");
            Route::get("/privacy-policy", "privacy")->name("privacy.index");
        });

        Route::get("/order-tracking", [
            ShippingController::class,
            "showTracking",
        ])->name("order.tracking");
    }
);

Route::controller(CheckoutController::class)->group(function () {
    Route::get("orders/{order}/invoice/download", "downloadInvoice")->name(
        "orders.invoice.download"
    );
});

Route::post("stripe/webhook", [
    StripeWebhookController::class,
    "handleWebhook",
])->name("cashier.webhook");

Route::post("/webhooks/tabby", [
    TabbyWebhookController::class,
    "handleWebhook",
])->name("webhooks.tabby");

Route::get("/cart/prefill", CartPrefillController::class)
    ->name("cart.prefill")
    ->middleware("signed");

Route::get("/s/{code}", function (string $code) {
    $url = app(UrlShortenerService::class)->getOriginalUrl($code);

    if (!$url) {
        abort(404);
    }

    return redirect($url);
})->name("short.redirect");
