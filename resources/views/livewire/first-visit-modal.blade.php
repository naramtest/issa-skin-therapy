<div
    {{-- TODO: fix animaton when close , design , only show on homepage  ,setup the newsletter backend  , --}}
    x-data="{
        show: false,
        init() {
            setTimeout(() => {
                this.show = @entangle("showModal")

                if (this.show) {
                    // First animate the content
                    gsap.fromTo(
                        '.modal-content',
                        {
                            opacity: 0,
                            scale: 0.5,
                        },
                        {
                            opacity: 1,
                            scale: 1,
                            duration: 0.5,
                            ease: 'power2.out',
                            delay: 0.3,
                            onComplete: () => {
                                // Create a more dynamic fold animation
                                gsap.timeline()
                                    // First slightly rotate and scale the container
                                    .fromTo(
                                        '.modal-image',
                                        {
                                            scaleX: 0,
                                            opacity: 1,
                                            rotateY: -15,
                                            transformPerspective: 1000,
                                        },
                                        {
                                            scaleX: 1,
                                            rotateY: 0,
                                            duration: 0.6,
                                            ease: 'power3.inOut',
                                        },
                                    )
                                    // Animate the image inside with a subtle perspective effect
                                    .fromTo(
                                        '.modal-image img',
                                        {
                                            scaleX: 1.5,
                                            opacity: 0,
                                            rotateY: 5,
                                        },
                                        {
                                            scaleX: 1,
                                            opacity: 1,
                                            rotateY: 0,
                                            duration: 0.5,
                                            ease: 'power2.out',
                                        },
                                        '-=0.4', // Start before the container animation ends
                                    )
                                    // Add a subtle shadow animation
                                    .fromTo(
                                        '.modal-image',
                                        {
                                            boxShadow: 'none',
                                        },
                                        {
                                            boxShadow:
                                                '-10px 0 20px rgba(0,0,0,0.1)',
                                            duration: 0.4,
                                            ease: 'power2.out',
                                        },
                                        '-=0.3',
                                    )
                            },
                        },
                    )
                }
            }, 1000)
        },
        close() {
            // Enhanced reverse animation
            gsap.timeline()
                .to('.modal-image', {
                    boxShadow: 'none',
                    duration: 0.3,
                })
                .to('.modal-image img', {
                    scaleX: 1.5,
                    opacity: 0,
                    rotateY: 5,
                    duration: 0.4,
                    ease: 'power2.in',
                })
                .to('.modal-image', {
                    scaleX: 0,
                    rotateY: -15,
                    duration: 0.5,
                    ease: 'power3.inOut',
                    onComplete: () => {
                        gsap.to('.modal-content', {
                            opacity: 0,
                            scale: 0.9,
                            duration: 0.4,
                            ease: 'power2.in',
                            onComplete: () => {
                                $wire.closeModal()
                            },
                        })
                    },
                })
        },
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50"
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50" @click="close()"></div>

    <!-- Modal -->
    <div class="fixed bottom-8 right-8 flex h-[55vh] w-[55vw] flex-col">
        <!-- Content -->
        <div class="flex h-full overflow-hidden">
            <!-- Left side - Image -->
            <div
                class="modal-image relative h-full w-[40%] origin-right overflow-hidden opacity-0"
                style="transform-style: preserve-3d"
            >
                <img
                    src="{{ asset("storage/images/newsletter.webp") }}"
                    alt="Welcome"
                    class="h-full w-full origin-left rounded-s-[20px] object-cover"
                />
            </div>

            <!-- Right side - Content -->
            <div
                class="modal-content flex w-[60%] flex-col rounded-e-[20px] bg-white p-8 shadow-xl"
            >
                <div class="mb-auto">
                    <h2 class="mb-4 text-3xl font-bold">
                        Share your before and after and get a nice gift!
                    </h2>
                </div>

                <!-- Form -->
                <div class="mt-6">
                    <div class="relative">
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="Enter your email"
                            class="w-full rounded-none border-gray-300 bg-gray-100 py-3 pl-4 pr-12 text-sm placeholder:text-gray-500 focus:border-gray-300 focus:ring-0"
                        />
                        <button
                            wire:click="subscribe"
                            class="absolute inset-y-0 right-0 flex items-center px-4 hover:text-gray-600"
                        >
                            <svg
                                class="h-5 w-5 text-black"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M5 12H19M19 12L12 5M19 12L12 19"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </div>
                    @error("email")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <p class="text-sm text-gray-600">
                    Subscribe to our newsletter and be the first to hear about
                    our new arrivals, special promotions and online exclusives.
                </p>

                <!-- Social Links -->
                <div class="mt-6 flex items-center space-x-4">
                    <!-- Social icons remain the same -->
                </div>
            </div>
        </div>
    </div>
</div>
