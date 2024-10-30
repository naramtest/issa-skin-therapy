import "./bootstrap";
import {
    Alpine,
    Livewire,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

import Swiper from "swiper";
import { Autoplay } from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";

window.Alpine = Alpine;
window.Autoplay = Autoplay;

window.Swiper = Swiper;

Livewire.start();
