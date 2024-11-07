<?php

namespace App\View\Components\Icons;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectArrowDown extends Component
{
    public function render(): View
    {
        return view("components.icons.select-arrow-down");
    }
}
