import PhotoSwipeLightbox from "photoswipe/lightbox";
import "photoswipe/style.css";

export const initLightbox = () => {
    const lightbox = new PhotoSwipeLightbox({
        gallery: "#gallery",
        children: "a",
        pswpModule: () => import("photoswipe"),
    });
    lightbox.init();
};
