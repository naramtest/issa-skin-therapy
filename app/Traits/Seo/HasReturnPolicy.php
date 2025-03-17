<?php

namespace App\Traits\Seo;

use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\MerchantReturnPolicy;
use Spatie\SchemaOrg\Schema;

trait HasReturnPolicy
{
    public static function getMerchantReturnPolicy(): MerchantReturnPolicy
    {
        // Add Merchant Return Policy (as requested by Google)
        return Schema::merchantReturnPolicy()
            ->name("Damage/Defect Return Policy")
            ->inStoreReturnsOffered(false)
            ->merchantReturnDays(7)
            ->returnMethod("ReturnByMail")
            ->returnPolicyCategory("MerchantReturnFiniteReturnWindow")
            ->returnShippingFeesAmount(
                Schema::monetaryAmount()
                    ->currency(CurrencyHelper::getCurrencyCode())
                    ->value("0.00")
            )
            ->restockingFee(0)
            ->returnFees(false)
            ->refundType(["ExchangeRefund", "MoneyBack"])
            ->applicableCountry("AE")
            ->url(URL::route("return.index"));
    }
}
