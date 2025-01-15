<div class="padding-from-side-menu bg-lightColor py-12">
    <form wire:submit="placeOrderAndPay" class="mx-auto">
        <div class="grid grid-cols-1 gap-x-6 lg:grid-cols-[56%_auto]">
            <!-- Left Column - Information Form -->
            <x-checkout.sections.form :form="$form" />

            <!-- Right Column - Order Summary -->
            <div class="relative rounded-lg">
                <div class="sticky top-[90px]">
                    <x-checkout.sections.summary
                        :cart-items="$this->cartItems"
                        :subtotal="$this->subtotal"
                        :total="$this->total"
                        :selected-shipping-rate="$selectedShippingRate"
                        :shipping-rates="$shippingRates"
                    />
                    <!-- Coupon Code -->
                    <x-checkout.sections.coupon />

                    <!-- Payment -->
                    <x-checkout.sections.payment :error="$error" />
                </div>
            </div>
        </div>
    </form>
</div>
