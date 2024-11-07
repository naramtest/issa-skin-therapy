export const initSelectAnimations = () => {
    return {
        setupSelectAnimations() {
            return {
                openSelectPanel() {
                    gsap.fromTo(
                        ".select-options-panel",
                        {
                            y: "100%",
                        },
                        {
                            y: "0%",
                            duration: 0.5,
                            ease: "power3.out",
                        },
                    );
                },

                closeSelectPanel() {
                    return gsap.to(".select-options-panel", {
                        y: "100%",
                        duration: 0.4,
                        ease: "power3.in",
                    });
                },
            };
        },
    };
};
