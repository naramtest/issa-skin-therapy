<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\Affiliate\AffiliateService;

readonly class OrderObserver
{
    public function __construct(protected AffiliateService $affiliateService)
    {
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty("status")) {
            $this->affiliateService->updateCommissionFromOrder($order);
        }
    }
}
