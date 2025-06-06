<?php

namespace App\Livewire;

use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Collection;
use Livewire\Component;

class CurrencySelector extends Component
{
    public string $location;
    public Collection $currencies;
    public string $selectedCurrency;
    public bool $isMobile = false;

    public function mount()
    {
        $this->currencies = collect(CurrencyHelper::getAvailableCurrencies());
        $this->selectedCurrency = CurrencyHelper::getUserCurrency();
    }

    public function render()
    {
        return view("livewire.currency-selector");
    }

    public function selectCurrency(string $code): void
    {
        $this->selectedCurrency = $this->currencies->firstWhere("code", $code)[
            "code"
        ];
        CurrencyHelper::setUserCurrency($this->selectedCurrency);
        $this->redirect(request()->header("Referer"));
    }
}
