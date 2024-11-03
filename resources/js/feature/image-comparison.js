// resources/js/feature/image-comparison.js
export const initImageComparison = () => {
    const comparisons = document.querySelectorAll(".image-comparison");

    comparisons.forEach((container) => {
        const slider = container.querySelector(".comparison-slider");
        const textOverlay = container.querySelector(".text-overlay");
        const beforeImage = container.querySelector(".before-image");
        const afterImage = container.querySelector(".after-image");

        let isSliding = false;

        // Handle mouse and touch events
        const startSliding = (e) => {
            isSliding = true;
            container.classList.add("sliding");
            if (textOverlay) {
                textOverlay.classList.add("opacity-0");
                textOverlay.classList.remove("opacity-100");
            }
        };

        const stopSliding = (e) => {
            isSliding = false;
            container.classList.remove("sliding");
            if (textOverlay) {
                textOverlay.classList.add("opacity-100");
                textOverlay.classList.remove("opacity-0");
            }
        };

        const slide = (e) => {
            if (!isSliding) return;

            const rect = container.getBoundingClientRect();
            const x = e.type === "mousemove" ? e.clientX : e.touches[0].clientX;
            const position = Math.max(
                0,
                Math.min(1, (x - rect.left) / rect.width),
            );

            slider.style.left = `${position * 100}%`;
            beforeImage.style.clipPath = `inset(0 ${100 - position * 100}% 0 0)`;
        };

        // Mouse events
        slider.addEventListener("mousedown", startSliding);
        document.addEventListener("mousemove", slide);
        document.addEventListener("mouseup", stopSliding);

        // Touch events
        slider.addEventListener("touchstart", startSliding);
        document.addEventListener("touchmove", slide);
        document.addEventListener("touchend", stopSliding);
    });
};
