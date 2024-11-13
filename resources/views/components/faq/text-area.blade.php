<div {{ $attributes->class(["rounded-[10px] bg-[#F4F4F4] py-[1px]"]) }}>
    <label class="sr-only" for="message">{{ __("store.Your Message") }}</label>
    <textarea
        class="block w-full bg-transparent px-5 pb-3 pt-1 text-sm placeholder:text-sm focus:outline-none"
        type="text"
        name="message"
        id="message"
        placeholder="{{ __("store.Message") }}"
        rows="5"
    ></textarea>
</div>
