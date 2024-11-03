export default () => ({
    async init() {
        // Now using the globally available GSAP from app.js
        const marqueeText = this.$refs.marqueeText;
        const originalContent = marqueeText.innerHTML;
        const isRTL = document.documentElement.getAttribute("dir") === "rtl";

        // Clone the text
        marqueeText.innerHTML =
            originalContent + originalContent + originalContent;

        // Create the animation
        gsap.to(marqueeText, {
            x: isRTL ? "33.33%" : "-33.33%",
            duration: 5,
            ease: "none",
            repeat: -1,
        });
    },
});
