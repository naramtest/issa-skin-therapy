export const initHeaderAnimations = () => {
    return {
        setupMobileMenuAnimations() {
            return {
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
                        delay: 0.05,
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
        },
    };
};
