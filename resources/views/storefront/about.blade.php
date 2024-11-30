<x-store-main-layout>
    <main class="relative">
        <x-about.hero />
        <x-home.section-container
            class="relative z-10  -translate-y-8 bg-lightColor py-14"
        >
            <section class="flex items-center gap-12 content-x-padding">
                <img src="{{asset('storage/images/about/dr-issa.webp')}}" class="object-cover w-[55%] rounded-2xl"
                     alt="">
                <div>
                    <h2 class="headline-font">About Us</h2>
                    <p class="mt-[30px] text-[19px] leading-[26px]">ISSA Skintherapy is a tribute to the unparalleled
                        expertise of Dr. Issa
                        Bachour. As a
                        trailblazing dermatologist, he dedicated his career to understanding the intricacies of skin
                        health and formulating creams that reflected a perfect blend of science and compassion. ISSA
                        carries forward this legacy with a commitment to quality and efficacy.</p>
                </div>
            </section>
            <section class="content-x-padding mt-[80px]">
                <div class="flex items-start gap-12 p-[60px] bg-black rounded-2xl">
                    <div class="w-[45%] p-[30px] rounded-xl bg-[#454242] text-white">
                        <h2 class="headline-font">Early Years and Education</h2>
                        <p class="mt-[30px] text-[19px] leading-[26px] mb-[100px]">Born with an innate passion for
                            dermatology, Dr.
                            Issa Bachour
                            embarked on a journey that would
                            leave an indelible mark on the world of skincare and beauty. Graduating as a board-certified
                            Dermatologist from esteemed universities in France, he honed his craft in a country renowned
                            for
                            its commitment to beauty and wellness.</p>
                    </div>
                    <img src="{{asset('storage/images/about/dr-issa-2.webp')}}" class="object-cover w-[55%] rounded-2xl"
                         alt="">
                </div>

            </section>

            <section class="flex items-center gap-12 mt-[80px] content-x-padding">
                <img src="{{asset('storage/images/about/dr-issa.webp')}}" class="object-cover w-[55%] rounded-2xl"
                     alt="">
                <div>
                    <h2 class="headline-font">The Art of Formulation</h2>
                    <p class="mt-[30px] text-[19px] leading-[26px]">With a career spanning over 30 years, Dr. Issa
                        Bachour became a beacon of innovation in dermatological care. His expertise in formulating
                        creams for diverse skin conditions and beauty enhancement became the cornerstone of his
                        practice. Patients sought his personalized solutions, and his formulations garnered widespread
                        recognition for their efficacy and transformative impact.</p>
                </div>
            </section>
            <section
                class="flex gap-5 rounded-b-[20px] border-b-[1px] border-[#A6BCC599] pb-20  mt-[80px] content-x-padding text-[17px]">
                <div class="w-[30%]">
                    <div>Our Core Values</div>
                    <h2 class="headline-font mt-[20px]">
                        Crafted by Dermatologist
                    </h2>
                    <ol style="list-style: auto;
                        padding: revert;" class="mt-6">
                        <li>
                            Crafted by Dr. Issa: Dermatologist-Approved Skincare.
                        </li>
                        <li>
                            Revolutionary Patent Delivery Technology (PET Delivery).
                        </li>
                        <li>
                            Premium Quality, Made in USA.
                        </li>
                    </ol>
                </div>
                <div class="w-[70%] grid grid-cols-3 gap-[20px] text-white">
                    <div class="relative overflow-hidden">
                        <img class="h-[360px] w-full rounded-2xl" src="{{asset('storage/images/shop-bg.webp')}}"
                             alt="background">
                        <div style="backdrop-filter: blur(5px);"
                             class="absolute w-full bottom-0 rounded-2xl bg-[#ADADAD3D] px-7 py-5">
                            <p class="text-2xl font-medium">Crafted by Dermatologist</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden">
                        <img class="h-[360px] w-full rounded-2xl" src="{{asset('storage/images/01.webp')}}"
                             alt="background">
                        <div style="backdrop-filter: blur(5px);"
                             class="absolute w-full bottom-0 rounded-2xl bg-[#ADADAD3D] px-7 py-5">
                            <p class="text-2xl font-medium">Patent Delivery Technology</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden">
                        <img class="h-[360px] w-full rounded-2xl" src="{{asset('storage/images/about/about-3.webp')}}"
                             alt="background">
                        <div style="backdrop-filter: blur(5px);"
                             class="absolute w-full bottom-0 rounded-2xl bg-[#ADADAD3D] px-7 py-5">
                            <p class="text-2xl font-medium">Premium Quality,
                                Made in USA</p>
                        </div>
                    </div>
                </div>

            </section>
            <section class="mt-8">
                <x-marquee :repeat="15" :speed="50" :gap="50">
            <span>
                <x-icons.bigger-sign />
            </span>
                    <span class="black-text-stroke text-nowrap text-[130px] font-bold">
                {{ __("store.A-CLEAR") }}
            </span>
                    <span>
                <x-icons.bigger-sign />
            </span>
                    <span class="black-text-stroke text-nowrap text-[130px] font-bold">
                {{ __("store.X-AGE") }}
            </span>
                </x-marquee>
                <div class="flex flex-col items-center gap-7 mt-6 pb-4">
                    <img class="w-12 h-12" src="{{asset('storage/icons/qu.svg')}}" alt="icon">
                    <h2 class="text-3xl font-bold">A passion Turned Into A Successful Side Hustle
                    </h2>
                    <p class="text-[17px] italic">- DR. Julian Bachour, Founder of ISSA</p>
                </div>
            </section>

            <section class="mt-[40px] p-[80px] bg-black  text-white rounded-[20px] ">
                <div class="flex gap-8">
                    <h2 class="font-[800] w-[30%] pb-10 text-[95px] leading-[100px]">Our History</h2>
                    <div class="flex w-[70%]">
                        <img class="w-[40%] min-h-[420px]" src="{{asset('storage/images/about/our-history.webp')}}"
                             alt="background">
                        <div class="w-[60%] px-[50px] py-[60px] bg-[#202020] rounded-e-2xl">
                            <h3 class="font-medium text-[24px]">Healing in the Gulf
                            </h3>
                            <p class="text-[#8C92A4] mt-6">Venturing beyond borders, Dr. Issa Bachour contributed
                                significantly to the healthcare
                                landscape in Kuwait and other countries. His compassionate care and groundbreaking
                                formulations earned him the admiration of both patients and peers. His legacy in Kuwait
                                is a testament to his dedication to advancing dermatological practices in diverse
                                cultural contexts.</p>
                        </div>

                    </div>
                </div>
                <div class="flex w-[70%] mt-12">

                    <div class="w-[60%] px-[50px] py-[60px] bg-[#202020] rounded-s-2xl">
                        <h3 class="font-medium text-[24px]">A Life Well-Lived
                        </h3>
                        <p class="text-[#8C92A4] mt-6">In 2023, Dr. Issa Bachour left behind a legacy of healing and
                            beauty, having dedicated his life to the well-being of others. His untimely departure marked
                            the end of a chapter, but his impact resonates through the countless lives he touched and
                            the transformative creams that continue to carry his expertise forward. ISSA SkinTherapy is
                            not just a brand; it’s a tribute to Dr. Issa’s commitment to enhancing lives through
                            dermatological care. Rooted in his principles and passion, ISSA seeks to share the magic of
                            his formulations, ensuring that his legacy lives on in the radiant skin and confident smiles
                            of those who use his products.</p>
                    </div>
                    <img class="w-[40%] min-h-[420px]" src="{{asset('storage/images/about/life-well-lived.webp')}}"
                         alt="background">

                </div>
                <img class="mt-12" src="{{asset('storage/images/about/time.webp')}}" alt="">
            </section>
        </x-home.section-container>
    </main>
</x-store-main-layout>
