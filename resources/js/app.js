import "./bootstrap";
import { Alpine, Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import { initLightbox } from "./feature/lightbox.js";

import Swiper from "swiper";
import { Autoplay, EffectFade, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/effect-fade";
import { initImageComparison } from "./feature/image-comparison";
import { initHeaderAnimations } from "./components/headerAnimations";
import { initSelectAnimations } from "./components/selectAnimations";

import gsap from "gsap";

import ScrollTrigger from "gsap/ScrollTrigger";
import { initTextEffects } from "./feature/text-effects.js";
import collapse from "@alpinejs/collapse";

// Initialize core GSAP
gsap.registerPlugin(ScrollTrigger);
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

window.Alpine = Alpine;
window.Autoplay = Autoplay;

Alpine.plugin(collapse);
window.Pagination = Pagination;
window.EffectFade = EffectFade;

window.Swiper = Swiper;
Alpine.data("headerAnimations", initHeaderAnimations);
Alpine.data("selectAnimations", initSelectAnimations);

document.addEventListener("DOMContentLoaded", () => {
    initLightbox();
});

document.addEventListener("DOMContentLoaded", () => {
    initLightbox();
    initImageComparison(); // Add this line
});

initTextEffects();

Alpine.directive("button-animation", (el) => {
    const overlay = el.querySelector(".button-overlay");

    gsap.set(overlay, {
        y: "100%",
        clipPath: "ellipse(150% 150% at 50% 0%)"
    });

    const tl = gsap.timeline({ paused: true });

    // Show from bottom animation
    tl.to(overlay, {
        y: 0,
        clipPath: "ellipse(150% 150% at 50% 100%)",
        duration: 0.8,
        ease: "power2.inOut"
    });

    el.addEventListener("mouseenter", () => tl.play());
    el.addEventListener("mouseleave", () => {
        // Hide to top animation
        gsap.to(overlay, {
            y: "-100%",
            clipPath: "ellipse(150% 150% at 50% 0%)",
            duration: 0.8,
            ease: "power2.inOut"
        });
    });
});
Livewire.start();
