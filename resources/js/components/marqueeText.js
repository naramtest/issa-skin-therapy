// resources/js/components/marquee.js
export default function marquee({
    speed = 50,
    gap = 24,
    direction = "left",
    isRtl = false,
}) {
    return {
        init() {
            this.setupMarquee();
        },

        setupMarquee() {
            const content = this.$refs.marqueeContent;
            const contentWidth = content.offsetWidth;
            const duration = contentWidth / speed;

            // Adjust direction for RTL
            let effectiveDirection = direction;
            if (isRtl) {
                effectiveDirection = direction === "left" ? "right" : "left";
            }

            // Calculate positions based on effective direction
            const startX =
                effectiveDirection === "left" ? 0 : -contentWidth / 2;
            const endX = effectiveDirection === "left" ? -contentWidth / 2 : 0;

            // Reset position and handle RTL
            gsap.set(content, {
                x: startX,
                force3D: true, // Improve performance
            });

            // Create GSAP timeline
            const tl = gsap.timeline({
                repeat: -1,
                defaults: {
                    ease: "none",
                    force3D: true,
                },
            });

            tl.to(content, {
                x: endX,
                duration: duration,
            });

            // Pause animation when not in viewport
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            tl.play();
                        } else {
                            tl.pause();
                        }
                    });
                },
                {
                    threshold: 0,
                    rootMargin: "50px", // Add some margin for smoother transitions
                },
            );

            observer.observe(content);

            // Handle resize
            const handleResize = () => {
                tl.kill();
                this.setupMarquee();
            };

            window.addEventListener("resize", handleResize);

            // Cleanup
            return () => {
                observer.disconnect();
                tl.kill();
                window.removeEventListener("resize", handleResize);
            };
        },
    };
}
