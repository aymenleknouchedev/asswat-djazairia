<style>
    /* Scoped review-card styles — overrides any global .custom-* leftovers */
    .rv-list { display: flex; flex-direction: column; }
    .rv-card {
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-start !important;
        gap: 20px;
        padding: 10px 0;       /* 10px top + 10px bottom = 20px between cards */
        border: 0 !important;
    }
    .rv-card .rv-image {
        flex: 0 0 auto;
        width: 90px;
        height: 90px;
    }
    .rv-card .rv-image a { display: block; width: 100%; height: 100%; }
    .rv-card .rv-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
        border-radius: 50% !important;
    }
    .rv-card .rv-texts {
        flex: 1 1 auto;
        min-width: 0;
        display: flex !important;
        flex-direction: column !important;
        gap: 8px !important;
        margin: 0 !important;
    }
    .rv-card .rv-texts > * { margin: 0 !important; }
    .rv-card .rv-writer {
        font-size: 16px;
        font-family: asswat-medium;
        color: #555;
    }
    .rv-card .rv-writer a { color: inherit; text-decoration: none; }
    .rv-card .rv-writer a:hover { text-decoration: underline; }
    .rv-card .rv-title {
        font-size: 22px;
        line-height: 1.3;
        font-family: asswat-bold;
        color: #111;
    }
    .rv-card .rv-title a { color: inherit; text-decoration: none; }
    .rv-card .rv-title a:hover { text-decoration: underline; }
    .rv-card .rv-summary {
        font-size: 16px;
        line-height: 1.5;
        color: #555;
        font-family: asswat-regular;
    }
    .rv-card .rv-date {
        font-size: 14px;
        color: #999;
        font-family: asswat-regular;
    }
</style>

@foreach ($otherReviews as $review)
    @php
        $reviewWriter = $review->writers()->first();
        $writerImage = $reviewWriter && $reviewWriter->image
            ? $reviewWriter->image
            : asset('user/assets/images/b2.jpeg');
        $arabicMonths = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    @endphp
    <article class="rv-card">
        <div class="rv-image">
            <a href="{{ $reviewWriter ? route('writer.show', $reviewWriter->id) : route('news.show', $review->shortlink) }}">
                <img loading="lazy" decoding="async" src="{{ $writerImage }}"
                    alt="{{ $reviewWriter->name ?? 'كاتب' }}">
            </a>
        </div>

        <div class="rv-texts">
            @if ($reviewWriter)
                <div class="rv-writer">
                    <a href="{{ route('writer.show', $reviewWriter->id) }}">{{ $reviewWriter->name }}</a>
                </div>
            @endif

            <h2 class="rv-title">
                <a href="{{ route('news.show', $review->shortlink) }}">{{ $review->title }}</a>
            </h2>

            @if (!empty($review->summary))
                <p class="article-desc rv-summary">{{ $review->summary }}</p>
            @endif

            <div class="rv-date">
                {{ $review->created_at->locale('ar')->translatedFormat('d') }}
                {{ $arabicMonths[$review->created_at->month - 1] }}
                {{ $review->created_at->locale('ar')->translatedFormat('Y') }}
            </div>
        </div>
    </article>
@endforeach
