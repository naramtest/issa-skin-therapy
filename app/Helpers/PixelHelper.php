<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Currency\CurrencyHelper;

class PixelHelper
{
    public static function pixelContentArray(Order $order)
    {
        $collection = collect($order->items)->map(function (OrderItem $item) {
            $product = $item->purchasable;

            return [
                "id" => $product->facebook_id,
                "content_id" => $product->facebook_id,
                "quantity" => $item->quantity,
                "content_name" => $product->name,
                "price" => CurrencyHelper::decimalFormatter(
                    $product->current_money_price
                ),
            ];
        });

        return [
            "facebook" => $collection->select(["id", "quantity"])->values(),
            "tikTok" => $collection
                ->select(["content_id", "quantity", "content_name", "price"])
                ->values(),
        ];
    }
}
