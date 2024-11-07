<?php

namespace App\View\Components\Home\Section;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MarqueeLogos extends Component
{
    public function render(): View
    {
        return view("components.home.section.marquee-logos");
    }
}
