<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("legal.refund_returns_policy")) }}</title>
    </x-slot>
    @php
        $name = $info->name;
        $email = count($info->email) ? $info->email[0]["email"] : "";
        $phone = count($info->phone) ? $info->phone[0]["number"] : "";
    @endphp

    <main class="mx-auto w-[80%] py-10 text-[18px]">
        <x-legal.h1 title="{{ __('legal.refund_returns_policy') }}" />
        <p
            class="pt-8 text-xl font-bold md:text-2xl rtl:font-[500] rtl:md:text-xl"
        >
            {{ __("legal.policy_subtitle") }}
        </p>

        <x-legal.h2 content="{{ __('legal.general_terms') }}" />
        <p class="mt-4 text-[17px]">
            {{ __("legal.accepts_returns", ["name" => $name]) }}
        </p>

        <x-legal.ol>
            @foreach (__("legal.return_conditions") as $condition)
                <li>{{ $condition }}</li>
            @endforeach
        </x-legal.ol>

        <p class="mt-4 font-semibold">
            {{ __("legal.non_saleable_conditions_title") }}
        </p>

        <x-legal.ol>
            @foreach (__("legal.non_saleable_conditions") as $condition)
                <li>{{ $condition }}</li>
            @endforeach
        </x-legal.ol>

        <x-legal.ol>
            @foreach (__("legal.additional_conditions") as $condition)
                <li>{{ $condition }}</li>
            @endforeach
        </x-legal.ol>

        <p class="mt-4 font-semibold">
            {{ __("legal.contact_info_needed") }}
        </p>

        <x-legal.h2 content="{{ __('legal.returns_exchanges') }}" />
        <p class="mt-4 font-semibold">
            {{ __("legal.damaged_defective_title") }}
        </p>

        <x-legal.ol>
            @foreach (__("legal.returns_exchange_conditions") as $condition)
                <li>
                    {!! __($condition, ["phone" => $phone, "email" => $email]) !!}
                </li>
            @endforeach
        </x-legal.ol>

        <p class="mt-4">
            {{ __("legal.contact_support") }}
        </p>

        <x-legal.h2 content="{{ __('legal.cancellation') }}" />
        <p class="mt-4">
            {{ __("legal.cancellation_policy") }}
        </p>

        <x-legal.ol>
            @foreach (__("legal.cancellation_conditions") as $condition)
                <li>{{ $condition }}</li>
            @endforeach
        </x-legal.ol>

        <x-legal.h2 content="{{ __('legal.reimbursement') }}" />
        <p class="mt-4">
            {!! __("legal.reimbursement_info") !!}
        </p>

        <x-legal.h2 content="{{ __('legal.conditioning_shipping_rates') }}" />
        <x-legal.ol>
            @foreach (__("legal.shipping_conditions") as $condition)
                <li>{{ __($condition, ["name" => $name]) }}</li>
            @endforeach
        </x-legal.ol>
    </main>
</x-store-main-layout>
