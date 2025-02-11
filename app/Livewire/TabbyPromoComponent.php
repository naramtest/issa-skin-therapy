<?php

namespace App\Livewire;

use App\Services\Currency\CurrencyHelper;
use Livewire\Component;
use Money\Money;

class TabbyPromoComponent extends Component
{
    public string $source;
    public string $lang;
    public ?string $selector = "#TabbyPromo";
    public string $currency;
    public float $priceAmount = 0;

    public function mount(
        Money $price,
        string $source = "product",
        ?string $selector = null
    ) {
        $this->source = $source; // 'product' or 'cart'
        $this->lang = app()->getLocale();
        $this->currency = CurrencyHelper::getUserCurrency();
        $this->priceAmount = CurrencyHelper::decimalFormatter($price);

        if ($selector) {
            $this->selector = $selector;
        }
    }

    public function render()
    {
        return view("livewire.tabby-promo-component", [
            "publicKey" => config("services.tabby.public_key"),
            "merchantCode" => config("services.tabby.merchant_code"),
        ]);
    }
}
