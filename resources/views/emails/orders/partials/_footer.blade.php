{{-- resources/views/emails/orders/partials/_footer.blade.php --}}
<div class="footer">
    <p>{{ $translations["common"]["thank_you"] }}</p>
    <p>{{ $translations["common"]["questions"] }}</p>
    <div style="margin-top: 20px">
        <p style="font-size: 12px; color: #999999">
            {{ config("app.name") }}
            <br />
            {{ config("store.address.address") }}
            <br />
            {{ config("store.address.city") }},
            {{ config("store.address.country") }}
        </p>
    </div>
</div>
