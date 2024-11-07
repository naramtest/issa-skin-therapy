<?php

namespace App\View\Components\Layout\Header;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnnounceBar extends Component
{
    public function render(): View
    {
        return view("components.layout.header.announce-bar");
    }
}
