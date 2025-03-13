@php
    $customerID = $getState();
    $customer = \App\Models\Customer::find($customerID);
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :state-path="$getStatePath()"
>
    @unless (! $customer)
        <a
            href="{{
                route(\App\Filament\Resources\UserResource\Pages\EditUser::getRouteName(), [
                    "record" => $customer->user_id,
                ])
            }}"
        >
            <div
                class="fi-fo-placeholder leading-6 underline transition-colors hover:text-gray-500"
            >
                {{ $customer->user->name }}
            </div>
        </a>
    @endunless
</x-dynamic-component>
