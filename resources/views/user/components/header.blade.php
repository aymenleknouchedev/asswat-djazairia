<style>
    /* ==========================================================================
       Top section ("En ce moment" layout)

       Row 1 — a wide feature (image beside its title + summary) and a narrower
               side column.
       Row 2 — four equal cards.

       That is six slots, but the section is fed seven contents. The seventh
       goes in the side column as a headline-only item under the side card:
       the tall feature leaves that column with spare height, and the site
       already uses image-less headline cards elsewhere, so it does not read
       as an extra shape.

       Columns are separated by gap alone — no rules between posts.
       ========================================================================== */

    .tc-section {
        --tc-gap: 24px;
    }

    /* ===== Row 1 ===== */
    .tc-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: var(--tc-gap);
    }

    .tc-feature {
        display: grid;
        /* 55% is chosen so the media column's 16/9 height lands on the side
           column's natural height. Narrower than that and the row is sized by
           the side column instead, which stretches the image off 16/9. */
        grid-template-columns: minmax(0, 55%) minmax(0, 1fr);
        gap: var(--tc-gap);
        /* stretch, not start: the media column has to reach the full row
           height, otherwise the image stops short of the side column and
           leaves a gap under it. */
        align-items: stretch;
    }

    /* aspect-ratio gives the image its intrinsic height, which is what sizes
       the row while heights are still indefinite; once the row is resolved
       height:100% takes over and the image fills it. */
    .tc-feature-media img {
        width: 100%;
        height: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    /* The text is far shorter than the image beside it. Rather than let the
       difference pool underneath (217px on live content), centre the block so
       the slack splits evenly above and below — the treatment the Axios
       "big things" layout uses for the same mismatch. */
    .tc-feature-body {
        align-self: center;
    }

    .tc-feature-body h2 {
        font-size: 26px;
        line-height: 1.35;
        margin: 0 0 10px;
        font-family: asswat-bold;
    }

    .tc-feature-body .article-desc {
        font-size: 15px;
        line-height: 1.6;
        color: #555;
        margin: 0;
    }

    /* ===== Row 1, side column ===== */
    .tc-side {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .tc-side-card img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
        margin-bottom: 8px;
    }

    /* The seventh content: a headline with no image, so it reads as a related
       headline rather than a card that lost its picture. */

    /* ===== Row 2 ===== */
    .tc-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: var(--tc-gap);
        margin-top: var(--tc-gap);
    }

    .tc-card img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
        margin-bottom: 8px;
    }

    /* ===== Shared type ===== */
    .tc-hero h3,
    .tc-row h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .tc-side-card p,
    .tc-side-extra p,
    .tc-card p {
        font-size: 16px;
        line-height: 1.4;
        margin: 0;
        font-family: asswat-bold;
    }

    /* === Titles: underline + pointer === */
    .tc-feature-body h2:hover,
    .tc-side-card p:hover,
    .tc-side-extra p:hover,
    .tc-card p:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* === Categories: pointer only === */
    .tc-hero h3:hover,
    .tc-row h3:hover {
        cursor: pointer;
    }

    @media (max-width: 1150px) {
        .tc-section {
            --tc-gap: 16px;
        }

        .tc-hero {
            grid-template-columns: minmax(0, 1fr) 260px;
        }

        .tc-feature-body h2 {
            font-size: 22px;
        }
    }

    @media (max-width: 992px) {

        /* Feature stacks, side column drops under it, row 2 becomes 2x2. */
        .tc-hero {
            grid-template-columns: 1fr;
        }

        .tc-feature {
            grid-template-columns: 1fr;
        }

        .tc-side {
            flex-direction: row;
            gap: var(--tc-gap);
        }

        .tc-side>* {
            flex: 1 1 0;
        }

        .tc-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            row-gap: var(--tc-gap);
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
            $side = $topContents[1]->content;
            $sideExtra = $topContents[2]->content;
            $rowItems = [$topContents[3]->content, $topContents[4]->content, $topContents[5]->content, $topContents[6]->content];
        @endphp

        {{-- Row 1: feature + side column --}}
        <div class="tc-hero">
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
                    <a href="{{ route('news.show', $feature->shortlink) }}"
                        style="text-decoration: none; color: inherit;">
                        <h2>{{ $feature->title ?? '' }}</h2>
                    </a>
                    <p class="article-desc">{{ $feature->summary ?? '' }}</p>
                </div>
            </article>

            <div class="tc-side">
                <article class="tc-side-card">
                    <a href="{{ route('news.show', $side->shortlink) }}">
                        <img loading="lazy" decoding="async" src="{{ $tcImage($side) }}"
                            alt="{{ $side->title ?? '' }}">
                    </a>
                    <h3>
                        <x-category-links :content="$side" />
                    </h3>
                    <a href="{{ route('news.show', $side->shortlink) }}"
                        style="text-decoration: none; color: inherit;">
                        <p>{{ $side->title ?? '' }}</p>
                    </a>
                </article>

                {{-- Seventh content --}}
                <article class="tc-side-extra">
                    <h3>
                        <x-category-links :content="$sideExtra" />
                    </h3>
                    <a href="{{ route('news.show', $sideExtra->shortlink) }}"
                        style="text-decoration: none; color: inherit;">
                        <p>{{ $sideExtra->title ?? '' }}</p>
                    </a>
                </article>
            </div>
        </div>

        {{-- Row 2: four cards --}}
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
