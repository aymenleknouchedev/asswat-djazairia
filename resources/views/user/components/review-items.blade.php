{{-- Grid Section --}}
<div class="custom-grid">
    <div class="custom-cards-wrapper">
        {{-- أول دفعة من المقالات --}}
        @foreach ($otherReviews as $review)
            <a href="{{ route('news.show', $review->shortlink) }}" style="text-decoration: none; color: inherit;">
                <div class="custom-card">
                    <div class="custom-image">
                        <img loading="lazy" decoding="async" src="{{ $review->media()->wherePivot('type', 'main')->first()->path ?? asset('user/assets/images/b2.jpeg') }}" alt="خبر">
                    </div>
                    <div class="custom-texts">
                        <h2>{{ $review->title }}</h2>
                        <p class="article-desc">{{ $review->summary }}</p>
                        <span>
                            @if (isset($review->author))
                                بقلم: {{ $review->author->name }}
                            @else
                                بقلم: غير معروف
                            @endif
                        </span>
                    </div>
                </div>
            </a>
        @endforeach

        {{-- زر تحميل المزيد --}}
        <div class="text-center mt-3" id="load-more-container">
            <button class="photos-load-more-btn btn btn-primary" data-page="1">
                المزيد
            </button>
        </div>
    </div>
    <div></div>
</div>
