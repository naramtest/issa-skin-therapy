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
            [
                "name" => "Home",
                "title" => __("store.Home"),
                "route" => "home.index",
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
                "route" => "check.index",
            ],
            [
                "name" => "Order Tracking",
                "title" => __("store.Order Tracking"),
                "route" => "order.tracking",
            ],
        ];
    }
}
