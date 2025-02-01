<x-store-main-layout>
    <x-slot name="title">
        <title>{{ getPageTitle(__("legal.privacy_policy")) }}</title>
    </x-slot>
    @php
        $name = $info->name;
        $email = count($info->email) ? $info->email[0]["email"] : "";
        $phone = count($info->phone) ? $info->phone[0]["number"] : "";
    @endphp

    <main
        class="mx-auto px-4 py-10 text-[18px] leading-[50px] md:w-[75%] md:px-0"
    >
        <x-legal.h1 title="{{ __('legal.privacy_policy') }}" />

        <x-legal.h2 content="{{ __('legal.general_terms') }}" />
        <p class="mt-4 font-semibold">
            {{ __("legal.general_terms_content") }}
        </p>

        <p class="mt-4">
            {{ __("legal.privacy_collection_info") }}
        </p>

        <x-legal.h2 content="{{ __('legal.disclosure_utilization') }}" />
        <p class="mt-4">
            {{ __("legal.disclosure_content") }}
        </p>

        <x-legal.h2 content="{{ __('legal.payment_security') }}" />
        <p class="mt-4">
            {{ __("legal.payment_security_content") }}
        </p>

        <x-legal.h2 content="{{ __('legal.cookies') }}" />
        <p class="mt-4">
            {{ __("legal.cookies_content") }}
        </p>

        <x-legal.h2 content="{{ __('legal.aggregated_data') }}" />
        <p class="mt-4">
            {{ __("legal.aggregated_data_content") }}
        </p>

        <p class="mt-4">
            {{ __("legal.additional_disclosure") }}
        </p>

        <x-legal.h2 content="{{ __('legal.protection') }}" />
        <p class="mt-4">
            {{ __("legal.protection_content") }}
        </p>

        <x-legal.h2 content="{{ __('legal.assistant_websites') }}" />
        <p class="mt-4">
            {{ __("legal.assistant_websites_content") }}
        </p>

        <p class="mt-4">
            {{ __("legal.minors_policy") }}
        </p>

        <x-legal.h2 content="{{ __('legal.policy_changes') }}" />
        <p class="mt-4">
            {{ __("legal.policy_changes_content") }}
        </p>
    </main>
</x-store-main-layout>
