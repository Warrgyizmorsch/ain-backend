@props([
    'content' => [],
])

@php
    $count = count($content);
    $half = ceil($count / 2);

    $leftColumn = array_slice($content, 0, $half);
    $rightColumn = array_slice($content, $half);
@endphp
<section class="page-section scrollables">
    <div class="scrollable-section">
        <div class="scrollable-container">

            {{-- LEFT COLUMN --}}
            <div class="column">
                @foreach($leftColumn as $block)
                    <div class="content-box">
                        {!! $block !!}
                    </div>
                @endforeach
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="column">
                @foreach($rightColumn as $block)
                    <div class="content-box">
                        {!! $block !!}
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
