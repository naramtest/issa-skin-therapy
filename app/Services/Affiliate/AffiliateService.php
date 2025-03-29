<?php

namespace App\Services\Affiliate;

use App\Enums\CommissionStatus;
use App\Models\AffiliateCommission;
use App\Models\Coupon;
use App\Models\Order;
use Money\Money;

class AffiliateService
{
    /**
     * Track commission for an order if an affiliate coupon was used.
     */
    public function trackCommission(
        Order $order,
        ?Coupon $coupon = null
    ): ?AffiliateCommission {
        // If no coupon or not an affiliate coupon, return null
        if (!$coupon || !$coupon->isAffiliateCoupon()) {
            return null;
        }

        // Check if there's already a commission for this order
        $existingCommission = AffiliateCommission::where(
            "order_id",
            $order->id
        )->first();
        if ($existingCommission) {
            return $existingCommission;
        }

        // Create a new commission record
        $commission = AffiliateCommission::create([
            "affiliate_id" => $coupon->affiliate_id,
            "order_id" => $order->id,
            "coupon_id" => $coupon->id,
            "order_total" => $order->total,
            "commission_rate" => $coupon->commission_rate,
            "commission_amount" => $this->calculateCommissionAmount(
                $order->getMoneyTotal(),
                $coupon->commission_rate
            ),
            "status" => CommissionStatus::PENDING,
        ]);

        // Update the affiliate's total commission
        $commission->affiliate->increment(
            "total_commission",
            $commission->commission_amount
        );

        return $commission;
    }

    /**
     * Calculate commission amount based on order total and commission rate.
     */
    public function calculateCommissionAmount(
        Money $orderTotal,
        float $commissionRate
    ): int {
        return $orderTotal->multiply($commissionRate / 100)->getAmount();
    }

    /**
     * Update commission status based on order status.
     */
    public function updateCommissionFromOrder(Order $order): void
    {
        $commission = AffiliateCommission::where(
            "order_id",
            $order->id
        )->first();

        $commission?->updateStatusFromOrder();
    }
}
