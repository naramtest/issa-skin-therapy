<?php

namespace App\Models\Query;

use App\Enums\ProductStatus;
use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends Builder
{
    public function published(): self
    {
        return $this->where("status", ProductStatus::PUBLISHED)
            ->whereNotNull("published_at")
            ->where("published_at", "<=", now());
    }

    public function draft(): self
    {
        return $this->where("status", ProductStatus::DRAFT);
    }

    public function featured(): self
    {
        return $this->where("is_featured", true);
    }

    public function byOrder(): self
    {
        return $this->orderBy("order");
    }

    public function available(): self
    {
        return $this->where(function ($query) {
            $query->orWhereIn("stock_status", [
                StockStatus::IN_STOCK->value,
                StockStatus::LOW_STOCK->value,
                StockStatus::BACKORDER->value,
                StockStatus::PREORDER->value,
            ]);
        });
    }

    public function lowStock(): self
    {
        return $this->where("stock_status", StockStatus::LOW_STOCK->value);
    }

    public function outOfStock(): self
    {
        return $this->where("stock_status", StockStatus::OUT_OF_STOCK->value);
    }
}
