<div>
    @if (empty(trim($orderId)) or empty(trim($email)))
        <x-tracking.form />
    @else
        <x-tracking.info />
    @endif
</div>
