<style>
    /* ==========================================================================
       Top section

       Row 1 — the lead story on its own: image on one side, its text centred
               beside it.
       Row 2 — the next four stories as equal cards in a single row. The
               section is fed seven contents; the last two are not rendered.

       The lead keeps the full width to itself, so nothing else can force the
       row taller than the image and pull it off 16/9 — which is what the side
       column used to do. The text is centred because it is much shorter than
       the image; centring splits the slack above and below instead of letting
       it pool underneath.

       Columns are separated by gap alone — no rules between posts.
       ========================================================================== */

    .tc-section {
        --tc-gap: 24px;
    }

    /* ===== Row 1: the lead story, alone ===== */
    .tc-feature {
        display: grid;
        grid-template-columns: minmax(0, 55%) minmax(0, 1fr);
        gap: var(--tc-gap);
        align-items: stretch;
    }

    .tc-feature-media img {
        width: 100%;
        height: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    .tc-feature-body {
        align-self: center;
    }

    .tc-feature-body h2 {
        font-size: 34px;
        line-height: 1.3;
        margin: 0 0 12px;
        font-family: asswat-bold;
    }

    .tc-feature-body .article-desc {
        font-size: 16px;
        line-height: 1.7;
        color: #555;
        margin: 0;
    }

    /* ===== Row 2: the next four ===== */
    .tc-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: var(--tc-gap);
        margin-top: calc(var(--tc-gap) * 1.5);
    }

    .tc-card img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
        margin-bottom: 8px;
    }

    /* ===== Shared type ===== */
    .tc-feature h3,
    .tc-row h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .tc-feature-body h3 {
        margin-top: 0;
    }

    .tc-card p {
        font-size: 18px;
        line-height: 1.4;
        margin: 0;
        font-family: asswat-bold;
    }

    /* === Titles: underline + pointer === */
    .tc-feature-body h2:hover,
    .tc-card p:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* === Categories: pointer only === */
    .tc-feature h3:hover,
    .tc-row h3:hover {
        cursor: pointer;
    }

    @media (max-width: 1150px) {
        .tc-section {
            --tc-gap: 16px;
        }

        .tc-feature-body h2 {
            font-size: 28px;
        }

        .tc-card p {
            font-size: 16px;
        }
    }

    @media (max-width: 992px) {

        /* Safety net only: the section lives inside .web, hidden under 992px. */
        .tc-feature {
            grid-template-columns: 1fr;
        }

        .tc-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>

@php
    // Main image for a content, or '' when it has none.
    $tcImage = fn($content) => $content->media()->wherePivot('type', 'main')->first()->path ?? '';
@endphp

<section class="news-feature-grid tc-section" id="news-feature-grid">
    @if (isset($topContents) && count($topContents) >= 7)
        @php
            $feature = $topContents[0]->content;
            // Contents 2-5. The sixth and seventh are intentionally not shown.
            $rowItems = collect($topContents)->slice(1, 4)->map(fn($t) => $t->content);
        @endphp

        {{-- Row 1: the lead story on its own --}}
        <article class="tc-feature">
            <div class="tc-feature-media">
                <a href="{{ route('news.show', $feature->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $tcImage($feature) }}"
                        alt="{{ $feature->title ?? '' }}">
                </a>
            </div>
            <div class="tc-feature-body">
                <h3>
                    <x-category-links :content="$feature" />
                </h3>
                <a href="{{ route('news.show', $feature->shortlink) }}" style="text-decoration: none; color: inherit;">
                    <h2>{{ $feature->title ?? '' }}</h2>
                </a>
                <p class="article-desc">{{ $feature->summary ?? '' }}</p>
            </div>
        </article>

        {{-- Row 2: the next four --}}
        <div class="tc-row">
            @foreach ($rowItems as $item)
                <article class="tc-card">
                    <a href="{{ route('news.show', $item->shortlink) }}">
                        <img loading="lazy" decoding="async" src="{{ $tcImage($item) }}"
                            alt="{{ $item->title ?? '' }}">
                    </a>
                    <h3>
                        <x-category-links :content="$item" />
                    </h3>
                    <a href="{{ route('news.show', $item->shortlink) }}"
                        style="text-decoration: none; color: inherit;">
                        <p>{{ $item->title ?? '' }}</p>
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</section>
