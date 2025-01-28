<div
    {{ $attributes->class([" !flex flex-col items-center text-white"]) }}
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="60"
        height="60"
        viewBox="0 0 60 60"
        fill="none"
    >
        <path
            d="M47.625 23.9247C48.8625 20.8123 50.8125 17.7372 53.4187 14.7747C54.2437 13.8372 54.3562 12.4872 53.6813 11.4372C53.1562 10.6122 52.2938 10.1623 51.3563 10.1623C51.0938 10.1623 50.8312 10.1809 50.5687 10.2748C45.0563 11.8872 32.1749 17.6059 31.8187 35.9435C31.6875 43.0122 36.8624 49.0872 43.5938 49.7809C47.325 50.1559 51.0374 48.9373 53.7938 46.4622C56.5499 43.9684 58.125 40.4059 58.125 36.6934C58.125 30.506 53.7374 25.0872 47.625 23.9247Z"
            fill="white"
        ></path>
        <path
            d="M13.669 49.7809C17.3816 50.1559 21.0941 48.9373 23.8503 46.4622C26.6066 43.9684 28.1816 40.4059 28.1816 36.6934C28.1816 30.506 23.7941 25.0872 17.6816 23.9247C18.9191 20.8123 20.8691 17.7372 23.4754 14.7747C24.3004 13.8372 24.4129 12.4873 23.7378 11.4373C23.2128 10.6123 22.3503 10.1623 21.4128 10.1623C21.1504 10.1623 20.8879 10.1809 20.6253 10.2748C15.1128 11.8872 2.23159 17.606 1.87534 35.9435V36.2059C1.87534 43.1622 6.99405 49.0872 13.669 49.7809Z"
            fill="white"
        ></path>
    </svg>
    <p
        class="mb-8 mt-6 text-center text-xl lg:text-4xl rtl:leading-[35px] rtl:lg:leading-[50px]"
    >
        {{ __("store.I’ve been using Lumicleanse for a few months now, I can say it’s a         game-changer for my skin. My skin feels fresher, cleaner, and more         radiant!") }}
    </p>
    <figure>
        <img
            class="w-[80px]"
            src="{{ asset("storage/images/issa-white.webp") }}"
            alt="{{ __("store.Logo") }}"
        />
    </figure>
    <p class="mt-4 text-lg font-light italic">Jonathan Smith, ISSA</p>
</div>
