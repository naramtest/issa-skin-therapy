import "./bootstrap";
import {
    Alpine,
    Livewire,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import { initLightbox } from "./feature/lightbox.js";

import Swiper from "swiper";
import { Autoplay, EffectFade, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/effect-fade";

window.Alpine = Alpine;
window.Autoplay = Autoplay;
window.Pagination = Pagination;
window.EffectFade = EffectFade;

window.Swiper = Swiper;
document.addEventListener("DOMContentLoaded", () => {
    initLightbox();
});
Livewire.start();
