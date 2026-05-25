@foreach ($experts as $expert)
    @php
        $reviews = json_decode($expert->customer_review, true);
        $totalReviews = is_array($reviews) ? count($reviews) : 0;
        $averageRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
    @endphp
    <div class="expert-item visible">
        <div class="testimonial-block-three-expert">
            <div class="inner-box-expert">
                <div class="image-outer">
                    <div class="image">
                        <a href="writers/{{$expert->slug}}">
                            <img src="{{ $expert->image ? asset($expert->image) : asset('assets/media/avatars/blank.png') }}" alt="{{$expert->name}}" />
                        </a>
                    </div>
                </div>
                <h6>{{$expert->name}}</h6>

                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-icon completed">✓</div>
                        <div class="stat-details">
                            <span class="stat-value">{{$expert->finish_order}}</span>
                            <span class="stat-label">Completed</span>
                        </div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-icon progress">⟳</div>
                        <div class="stat-details">
                            <span class="stat-value">{{$expert->inprogress_order}}</span>
                            <span class="stat-label">In Progress</span>
                        </div>
                    </div>
                </div>

                <div class="rating">
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $averageRating ? 'checked' : '' }}">★</span>
                        @endfor
                    </div>
                    <p class="rating-score">({{ $averageRating }}/5)</p>
                </div>

                <p class="content">{!! Str::limit(strip_tags($expert->content, '<strong><em>'), 90, '...') !!}</p>
            </div>
        </div>
    </div>
@endforeach
