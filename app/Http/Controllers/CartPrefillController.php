<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Services\Cart\CartService;
use Exception;
use Illuminate\Http\Request;
use Log;

class CartPrefillController extends Controller
{
    public function __invoke(Request $request, CartService $cartService)
    {
        try {
            // Validate the items array
            $items = collect($request->input("items", []));

            // Clear existing cart
            $cartService->clear();

            // Add each item to cart
            foreach ($items as $item) {
                $type = ProductType::tryFrom($item["type"]);

                if (!$type) {
                    continue;
                }

                $cartService->addItem(
                    type: $type,
                    id: $item["id"],
                    quantity: $item["qty"] ?? 1
                );
            }

            return redirect()
                ->route("checkout.index")
                ->with(
                    "success",
                    __("store.Items have been added to your cart")
                );
        } catch (Exception $e) {
            Log::error("Failed to prefill cart", [
                "error" => $e->getMessage(),
                "items" => $request->input("items"),
            ]);

            return redirect()
                ->route("shop.index")
                ->with("error", __("store.Failed to add items to cart"));
        }
    }
}
