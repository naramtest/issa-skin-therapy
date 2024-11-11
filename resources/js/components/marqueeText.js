export default function marquee({ speed = 50, gap = 24, direction = "left" }) {
    return {
        init() {
            this.setupMarquee();
        },

        setupMarquee() {
            const content = this.$refs.marqueeContent;
            const contentWidth = content.offsetWidth;
            const duration = contentWidth / speed;

            // Set initial position based on direction
            const startX = direction === "left" ? 0 : -contentWidth / 2;
            const endX = direction === "left" ? -contentWidth / 2 : 0;

            // Reset position
            gsap.set(content, { x: startX });

            // Create GSAP timeline for smooth infinite animation
            const tl = gsap.timeline({ repeat: -1 });

            tl.to(content, {
                x: endX,
                duration: duration,
                ease: "none",
            });

            // Pause animation when not in viewport for performance
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
                { threshold: 0 },
            );

            observer.observe(content);

            // Cleanup
            return () => {
                observer.disconnect();
                tl.kill();
            };
        },
    };
}
