<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function index()
    {
        return view("storefront.checkout", [
            "cartItems" => [
                [
                    "id" => 1,
                    "name" => "X-AGE Stem Booster",
                    "price" => 195.47,
                    "quantity" => 1,
                    "image" => asset("storage/test/product/product.webp"),
                    "subtitle" => "TREAT, X-AGE",
                ],
                [
                    "id" => 2,
                    "name" => "A-Clear Control lotion",
                    "price" => 63.5,
                    "quantity" => 2,
                    "image" => asset("storage/test/product/product.webp"),

                    "subtitle" => "A-Clear, TREAT",
                ],
                [
                    "id" => 3,
                    "name" => "A-Luminate Renewing Lotion",
                    "price" => 59.0,
                    "quantity" => 1,
                    "image" => asset("storage/test/product/product.webp"),

                    "subtitle" => "HYDRATE & PROTECT",
                ],
                [
                    "id" => 4,
                    "name" => "LumiGuard Broad Spectrum Emulsion",
                    "price" => 59.76,
                    "quantity" => 1,
                    "image" => asset("storage/test/product/product.webp"),

                    "subtitle" => "A-Luminate, HYDRATE & PROTECT",
                ],
            ],
        ]);
    }
}
