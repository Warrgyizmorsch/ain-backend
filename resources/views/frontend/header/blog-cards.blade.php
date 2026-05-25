<!-- @foreach ($blogs as $blog) -->
    <div class="col-12 col-md-6 mt-4">
        <div class="animated-border-wrapper">
            <div class="blog-card">
                <a href="{{ url('/blog', $blog->slug) }}">
                    <img src="{{ asset($blog->images) }}" alt="{{ $blog->tittle }}">
                </a>
                <div class="p-3">
                    <div class="blog-meta">
                        <div>
                            <img src="{{ asset('assets/icons/icons8-blog-16.png') }}" alt="Blog Icon" class="me-1"> Blog
                        </div>
                        <div>
                            <img src="{{ asset('assets/icons/icons8-date-16.png') }}" alt="Date Icon" class="me-1">
                            {{ $blog->created_at->format('F j, Y') }}
                        </div>
                    </div>
                    <h3 class="blog-title">
                        <a href="{{ url('/blog', $blog->slug) }}">{{ $blog->tittle }}</a>
                    </h3>
                    <p class="blog-description">
                        {{ Str::limit(strip_tags($blog->content), 50, '...') }}
                    </p>
                    <a href="{{ url('/blog', $blog->slug) }}" class="btn-learn-more">Learn More</a>
                </div>
            </div>
        </div>
    </div>
<!-- @endforeach -->
