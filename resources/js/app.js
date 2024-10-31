import "./bootstrap";
import {
    Alpine,
    Livewire,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

import Swiper from "swiper";
import { Autoplay, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";

window.Alpine = Alpine;
window.Autoplay = Autoplay;
window.Pagination = Pagination;

window.Swiper = Swiper;

Livewire.start();
