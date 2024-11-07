<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Marquee extends Component
{
    public function __construct(
        public int $speed = 50,
        public int $gap = 24,
        public int $repeat = 15
    ) {
    }

    public function render()
    {
        return view("components.marquee");
    }
}
