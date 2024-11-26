@dd($this->displayPosts)
{{-- Display the posts --}}

@foreach ($this->displayPosts as $index => $post)
    @switch($index)
        {{-- First post (large card) --}}
        @case(0)
            @break

            {{-- Second and third posts (medium cards) --}}
        @case(1)
        @case(2)
            @break

            {{-- Remaining posts (small cards) --}}
        @default
    @endswitch
@endforeach
