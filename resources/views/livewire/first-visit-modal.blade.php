{{-- TODO:  design  ,setup the newsletter backend  , --}}
<div
    x-data="{
        show: false,
        cursor: null,
        init() {
            // Reference to the custom cursor element
            this.cursor = document.getElementById('custom-cursor')

            // Track mouse movement for the custom cursor
            window.addEventListener('mousemove', (e) => {
                gsap.to(this.cursor, {
                    x: e.clientX,
                    y: e.clientY,
                    duration: 0.1,
                    ease: 'power2.out',
                })
            })

            // Overlay element reference
            const overlay = document.querySelector('.fixed.inset-0.bg-black\\/50')

            // Hover effect on 'mouseenter'
            overlay.addEventListener('mouseenter', () => {
                gsap.to(this.cursor, {
                    scale: 2,
                    backgroundColor: 'rgba(255, 255, 255)',
                    duration: 0.3,
                })

                // Ensure the custom cursor remains visible
                this.cursor.style.display = 'block'

                // Hide the default cursor
                overlay.style.cursor = 'none'
            })

            // Remove custom effect on 'mouseleave'
            overlay.addEventListener('mouseleave', () => {
                gsap.to(this.cursor, {
                    scale: 1,
                    backgroundColor: 'rgba(0, 0, 0, 0.5)',
                    duration: 0.3,
                })

                // Restore the default cursor
                overlay.style.cursor = 'pointer'

                // Hide the custom cursor
                this.cursor.style.display = 'none'
            })
            setTimeout(() => {
                this.show = @entangle("showModal")

                if (this.show) {
                    gsap.fromTo(
                        '.modal-content',
                        {
                            opacity: 0,
                            scale: 0.9,
                        },
                        {
                            opacity: 1,
                            scale: 1,
                            duration: 0.5,
                            ease: 'power2.out',
                            delay: 0.3,
                            onComplete: () => {
                                gsap.timeline()
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
                                        '-=0.4',
                                    )
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
            // Create a master timeline for closing
            const tl = gsap.timeline({
                onComplete: () => {
                    this.show = false
                    $wire.closeModal()
                },
            })

            // Add all animations to the timeline
            tl.to('.modal-image', {
                boxShadow: 'none',
                duration: 0.2,
            })
                .to('.modal-image img', {
                    scaleX: 1.5,
                    opacity: 0,
                    rotateY: 5,
                    duration: 0.3,
                    ease: 'power2.in',
                })
                .to(
                    '.modal-image',
                    {
                        scaleX: 0,
                        rotateY: -15,
                        duration: 0.4,
                        ease: 'power3.inOut',
                    },
                    '-=0.2',
                )
                .to(
                    '.modal-content',
                    {
                        opacity: 0,
                        scale: 0.9,
                        duration: 0.3,
                        ease: 'power2.in',
                    },
                    '-=0.3',
                )
        },
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50"
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50" @click="close()"></div>

    <!-- Modal -->
    <div
        class="fixed bottom-0 end-0 flex flex-col md:bottom-6 md:end-6 md:h-[50vh] md:w-[55vw]"
    >
        <!-- Content -->
        <div class="flex h-full overflow-hidden">
            <!-- Left side - Image -->
            <div
                class="modal-image relative hidden h-full w-[40%] origin-right overflow-hidden opacity-0 md:block"
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
                class="modal-content flex w-full flex-col justify-between gap-y-8 rounded-t-[20px] bg-white p-4 shadow-xl md:w-[60%] md:gap-y-0 md:!rounded-e-[20px] md:rounded-t-none md:p-8"
            >
                <button
                    @click="close()"
                    class="absolute end-4 top-4 z-10 hidden h-12 w-12 items-center justify-center rounded-full border border-gray-400 bg-white transition-transform hover:scale-110 md:flex"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
                <div
                    class="mx-auto h-[4px] w-[70px] rounded bg-[#1717171a] md:hidden"
                ></div>
                <h2 class="mt-2 w-[95%] text-3xl font-bold">
                    {{ __("store.Share your before and after") }}
                    <br />
                    {{ __("store.and get") }}
                    <span
                        x-data="{
                            startAnimation: false,
                            init() {
                                this.startAnimation = true
                                setInterval(() => {
                                    this.startAnimation = false
                                    setTimeout(() => (this.startAnimation = true), 100)
                                }, 8000)
                            },
                        }"
                        class="relative inline-block"
                    >
                        {{ __("store.a nice gift!") }}
                        <svg
                            x-show="startAnimation"
                            x-transition:enter="transition duration-100 ease-out"
                            class="animated-underline absolute -bottom-5 left-0 w-full"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 500 150"
                            preserveAspectRatio="none"
                            style="height: 40px"
                        >
                            <path
                                d="M0,75 Q125,75 250,75 T500,85"
                                stroke="#D5E1D1"
                                stroke-width="12"
                                fill="none"
                                stroke-linecap="round"
                            ></path>
                        </svg>
                    </span>
                </h2>
                <!-- Form -->
                <div>
                    <div class="relative">
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="{{ __("store.Enter your email") }}"
                            class="w-full rounded-lg border-gray-300 bg-gray-100 py-5 pe-12 ps-6 placeholder:text-gray-500 focus:border-gray-300 focus:ring-0"
                        />
                        <button
                            wire:click="subscribe"
                            class="absolute end-3 top-1/2 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-darkColor"
                        >
                            <x-icons.arrow-right class="h-5 w-5 text-white" />
                        </button>
                    </div>
                    @error("email")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <p class="text-sm text-gray-600">
                    {{ __("store.Subscribe to our newslet") }}
                </p>

                <!-- Social Links -->
                <div class="flex items-center space-x-4">
                    <!-- Social icons remain the same -->
                    <x-layout.header.home.social
                        class="flex gap-x-6"
                        color="text-black"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
