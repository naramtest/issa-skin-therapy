@php
    use App\Enums\Checkout\OrderStatus;

    $colorClasses = [
        OrderStatus::PENDING->value => "--c-50:var(--gray-50);--c-400:var(--gray-400);--c-600:var(--gray-600);",
        //        OrderStatus::DRAFT->value => "--c-50:var(--gray-50);--c-400:var(--gray-400);--c-600:var(--gray-600);",
        OrderStatus::PROCESSING->value => "--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);",
        OrderStatus::COMPLETED->value => "--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);",
        OrderStatus::CANCELLED->value => "--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);",
        OrderStatus::FAILED->value => "--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);",
        OrderStatus::REFUNDED->value => "--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);",
        OrderStatus::ON_HOLD->value => "--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);",
    ];

    $state = $getState();
    $statusClass = $colorClasses[$state] ?? "--c-50:var(--gray-50);--c-400:var(--gray-400);--c-600:var(--gray-600);";
    $status = OrderStatus::from($state);
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
    <div
        style="{{ $statusClass }}"
        class="fi-badge fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-warning flex w-fit min-w-[theme(spacing.6)] items-center justify-center gap-x-1 rounded-md px-4 py-1 text-xs font-medium ring-1 ring-inset"
    >
        <span class="font-semibold uppercase">{{ $status->getLabel() }}</span>
    </div>
</x-dynamic-component>
