<?php

namespace App\Livewire;

use App\Helpers\Money\UserCurrency;
use Illuminate\Support\Collection;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CurrencySelector extends Component
{
    public string $location;
    public Collection $currencies;
    public string $selectedCurrency;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->currencies = collect(UserCurrency::$currencies);
        $this->selectedCurrency = UserCurrency::get();
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
        UserCurrency::set($this->selectedCurrency);
        $this->redirect(request()->header("Referer"));
    }
}
