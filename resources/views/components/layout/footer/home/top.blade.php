<div
    {{ $attributes->class(["grid w-full grid-cols-4 items-center gap-x-4 divide-x divide-[#E3EAEC] rounded-b-[20px] border-t-[1px] border-[#a5bbc4] bg-lightColor p-10"]) }}
>
    <x-layout.footer.customer-care
        content="Email us or WhatsApp us for any concern and we will be happy to assist."
        title="Customer service"
        icon="{{asset('storage/icons/headphones.svg')}}"
        width="w-full"
    />

    <x-layout.footer.customer-care
        content="Free Worldwide International Shipping for orders over $180."
        title="Free Shipping"
        icon="{{asset('storage/icons/shipping.svg')}}"
    />

    <x-layout.footer.customer-care
        content="Within 7 days."
        title="Returns"
        icon="{{asset('storage/icons/return.svg')}}"
    />

    <x-layout.footer.customer-care
        content="Secure payment"
        title="We care about security, you payment is 100% secure here."
        icon="{{asset('storage/icons/secure.svg')}}"
    />
</div>
