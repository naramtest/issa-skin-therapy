<div>
    <div>
        <div id="{{ str_replace("#", "", $selector) }}"></div>

        @push("scripts")
            <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    new TabbyPromo({
                        selector: '{{ $selector }}',
                        currency: '{{ $currency }}',
                        price: '{{ $priceAmount }}',
                        lang: '{{ $lang }}',
                        source: '{{ $source }}',
                        publicKey: '{{ $publicKey }}',
                        merchantCode: '{{ $merchantCode }}',
                        theme: document.documentElement.classList.contains(
                            'dark',
                        )
                            ? 'dark'
                            : 'default',
                    });
                });
            </script>
        @endpush
    </div>
</div>
