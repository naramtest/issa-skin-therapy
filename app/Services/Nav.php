<?php

namespace App\Services;

class Nav
{
    public static function findByName(string $searchName): ?array
    {
        return collect(self::pages())->first(function ($item) use (
            $searchName
        ) {
            return strcasecmp($item["name"], $searchName) === 0;
        });
    }

    public static function pages(): array
    {
        return [
            "profile" => [
                [
                    "name" => "Home",
                    "title" => __("store.Home"),
                    "route" => "storefront.index",
                ],
                [
                    "name" => "Shop",
                    "title" => __("store.Shop"),
                    "route" => "shop.index",
                ],
                [
                    "name" => "My Account",
                    "title" => __("store.My Account"),
                    "route" => "account.index",
                ],
                [
                    "name" => "Checkout",
                    "title" => __("store.Checkout"),
                    "route" => "checkout.index",
                ],
                [
                    "name" => "Order Tracking",
                    "title" => __("store.Order Tracking"),
                    "route" => "order.tracking",
                ],
            ],
            "customer" => [
                [
                    "name" => "Help & Contact us",
                    "title" => __("store.Help & Contact us"),
                    "route" => "contact.index",
                ],
                [
                    "name" => "FAQ’s",
                    "title" => __("store.FAQ’s"),
                    "route" => "faq.index",
                ],
                [
                    "name" => "Blog",
                    "title" => __("store.Blog"),
                    "route" => "posts.index",
                ],
                [
                    "name" => "Refund Returns",
                    "title" => __("store.Refund Returns"),
                    "route" => "return.index",
                ],
            ],
            "info" => [
                [
                    "name" => "About Us",
                    "title" => __("store.About Us"),
                    "route" => "about.index",
                ],
                [
                    "name" => "Terms & Conditions",
                    "title" => __("store.Terms & Conditions"),
                    "route" => "terms.index",
                ],
                [
                    "name" => "Privacy & policy",
                    "title" => __("store.Privacy & policy"),
                    "route" => "privacy.index",
                ],
            ],
        ];
    }
}
