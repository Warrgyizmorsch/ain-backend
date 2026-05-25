 @foreach ($samples as $obj)
        <a href="free-samples/{{$categoryName}}/{{$obj->slug}}">
        <div class="sample-card mb-4">
        <h2 class="sample-title">{{ $obj->title }}</h2>
        <div class="sample-info">
        <span>Downloads: {{ $obj->downloads }}</span> |
        <span>Words: {{ str_word_count(strip_tags($obj->content)) }}</span> |
        @php
        $pageCount = ceil(str_word_count(strip_tags($obj->content)) / 250);
        @endphp
        <span>Pages: {{ $pageCount }}</span>
        </div>
        @php
        $cleanText = strip_tags($obj->content);
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);
        $limitedText = \Illuminate\Support\Str::limit(trim($cleanText), 150, '...');
            @endphp
        <p class="sample-description">{{ $limitedText }}</p>
        <p class="download-link">View or Download →</p>
        </div>
        </a>
            @endforeach