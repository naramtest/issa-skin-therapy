export default function marquee({ speed = 50, gap = 24 }) {
    return {
        init() {
            this.setupMarquee();
        },

        setupMarquee() {
            const content = this.$refs.marqueeContent;
            const contentWidth = content.offsetWidth;
            const duration = contentWidth / speed;

            // Create GSAP timeline for smooth infinite animation
            const tl = gsap.timeline({ repeat: -1 });

            tl.to(content, {
                x: -contentWidth / 2,
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
        },
    };
}
