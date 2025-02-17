{{-- resources/views/livewire/alert.blade.php --}}
<div>
    <div
        x-data="{
            timeout: null,
        }"
        x-effect="
            () => {
                console.log('Effect running', $wire.show)
                if ($wire.show) {
                    if (timeout) clearTimeout(timeout)
                    timeout = setTimeout(() => {
                        $wire.hideAlert()
                    }, 5000)
                }
            }
        "
        x-show="$wire.show"
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="scale-90 transform opacity-0"
        x-transition:enter-end="scale-100 transform opacity-100"
        x-transition:leave="transition duration-300 ease-in"
        x-transition:leave-start="scale-100 transform opacity-100"
        x-transition:leave-end="scale-90 transform opacity-0"
        class="fixed right-4 top-4 z-50 w-96 rounded-lg shadow-lg"
        role="alert"
    >
        <div
            @class([
                "rounded-lg p-4",
                "bg-red-100 text-red-900" => $type === "error",
                "bg-green-100 text-green-900" => $type === "success",
                "bg-blue-100 text-blue-900" => $type === "info",
                "bg-yellow-100 text-yellow-900" => $type === "warning",
            ])
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if ($type === "error")
                        <svg
                            class="h-5 w-5 text-red-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    @elseif ($type === "success")
                        <svg
                            class="h-5 w-5 text-green-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    @endif
                    <p class="ml-3 text-sm font-medium">{{ $message }}</p>
                </div>
                <button
                    wire:click="hideAlert"
                    class="ml-4 inline-flex text-gray-400 hover:text-gray-900 focus:outline-none"
                >
                    <svg
                        class="h-5 w-5"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
