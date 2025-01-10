<div
    {{ $attributes->class(["grid w-full grid-cols-1 items-center gap-x-4 gap-y-6 divide-[#E3EAEC] rounded-b-[20px] border-t-[1px] border-[#a5bbc4] bg-lightColor p-10 lg:grid-cols-4 lg:divide-x justify-center lg:justify-start "]) }}
>
    <x-layout.footer.customer-care
        content="{{ __('store.Email us or WhatsApp us for any concern and we will be happy to assist') }}."
        title="{{ __('store.Customer service') }}"
        icon="{{asset('storage/icons/headphones.svg')}}"
        width="w-fit lg:w-full"
    />

    <x-layout.footer.customer-care
        content="{{ __('store.Free Worldwide International Shipping for orders over $180') }}."
        title="{{ __('store.Free Shipping') }}"
        icon="{{asset('storage/icons/shipping.svg')}}"
    />

    <x-layout.footer.customer-care
        content="{{ __('store.Within 7 days') }}."
        title="{{ __('store.Returns') }}"
        icon="{{asset('storage/icons/return.svg')}}"
    />

    <x-layout.footer.customer-care
        content=" {{ __('store.We care about security, you payment is 100% secure here') }}."
        title="{{ __('store.Secure payment') }}"
        icon="{{asset('storage/icons/secure.svg')}}"
    />
</div>
