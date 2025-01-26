export const initHeaderAnimations = () => {
    return {
        // Desktop Animation
        animated: false,
        animateItems() {
            gsap.to(this.$refs.divider, {
                width: "100%",
                duration: 0.9,
                ease: "power1.in",
                delay: 0.5,
            });
            gsap.fromTo(
                ".menu-item",
                {
                    opacity: 0,
                    x: 50,
                },
                {
                    opacity: 1,
                    x: 0,
                    duration: 0.3,
                    stagger: 0.1,
                    ease: "power2.out",
                    onComplete: () => {
                        this.animated = true; // Mark as animated when complete
                    },
                },
            );
            // Animate card content
            gsap.fromTo(
                ".card-content",
                {
                    x: 30,
                    opacity: 0,
                },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.6,
                    delay: 0.3,
                    ease: "power2.in",
                },
            );
        },
        resetAnimations() {
            this.animated = false; // Reset animation state
            gsap.set(".menu-item", {
                opacity: 0,
                x: 20,
            });
            gsap.set(this.$refs.divider, {
                width: 0,
            });
            gsap.set(".card-content", {
                x: 30,
                opacity: 0,
            });
        },
        // Mobile Animation
        openMenu() {
            document.body.style.overflow = "hidden";

            // Overlay animation
            gsap.to(".menu-overlay", {
                opacity: 1,
                duration: 0.3,
                display: "block",
            });

            // Panel slide up animation
            gsap.to(".menu-panel", {
                y: "0%",
                duration: 0.5,
                ease: "power3.out",
            });

            // Menu items stagger animation
            gsap.to(".menu-items li", {
                y: 0,
                opacity: 1,
                stagger: 0.1,
                duration: 0.4,
                delay: 0.2,
            });
        },

        closeMenu() {
            document.body.style.overflow = "";

            // Fade out overlay
            gsap.to(".menu-overlay", {
                opacity: 0,
                duration: 0.3,
                display: "none",
            });

            // Slide down panel
            gsap.to(".menu-panel", {
                y: "100%",
                duration: 0.5,
                ease: "power3.in",
            });

            // Fade out menu items
            gsap.to(".menu-items li", {
                y: 50,
                opacity: 0,
                duration: 0.3,
            });
        },
    };
};
