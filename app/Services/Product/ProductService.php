<?php

namespace App\Services\Product;

use App\Enums\ProductStatus;
use App\Models\Product;

class ProductService
{
    public function handleSaving(Product $product): void
    {
        if ($product->is_featured) {
            Product::where("id", "!=", $product->id)
                ->where("is_featured", true)
                ->update(["is_featured" => false]);
        }

        if (
            $product->status === ProductStatus::PUBLISHED &&
            empty($product->published_at)
        ) {
            $product->published_at = now();
        }

        if (
            $product->status === ProductStatus::DRAFT &&
            $product->getOriginal("status") === ProductStatus::PUBLISHED->value
        ) {
            $product->published_at = null;
        }
    }
}
