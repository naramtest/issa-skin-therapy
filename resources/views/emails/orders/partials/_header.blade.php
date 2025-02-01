{{-- resources/views/emails/orders/partials/_header.blade.php --}}
<div class="email-header">
    <img
        src="{{ asset("storage/images/issa-logo.webp") }}"
        alt="Logo"
        class="logo"
    />
    <h1 class="header-title">{{ $translations["status"]["title"] }}</h1>
    <p>
        {{ trans("emails.orders.status.common.greeting", ["name" => $billingAddress->full_name]) }}
    </p>
</div>
