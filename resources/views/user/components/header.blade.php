<style>
    .news-grid-container {
        display: grid;
        grid-template-columns: 8fr 2fr 2fr;
        gap: 20px;
    }

    .news-list {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }


    .news-item {
        height: 250px;
    }

    .news-item-noimage {
        height: 110px;
    }

    .news-item-noimage h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .news-item-noimage p {
        font-size: 16px;
        font-family: asswat-bold;
    }

    .news-feature h3 {
        font-size: 12px;
        margin: 0px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .news-item img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        display: block;
    }

    .news-item h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .news-item p {
        font-size: 16px;
        font-family: asswat-bold;
    }

    .news-feature {
        position: relative;
    }

    .news-feature img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
    }

    .news-feature h2 {
        font-size: 24px;
        margin: 0px 0px 8px 0;
        font-family: asswat-bold;
    }

    .news-feature h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;

    }

    .news-feature p {
        font-size: 17px;
        color: #555;
    }

    /* === Titles: underline + pointer === */
    .news-feature h2:hover,
    .news-item p:hover,
    .news-item-noimage p:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* === Categories: pointer only === */
    .news-feature h3:hover,
    .news-item h3:hover,
    .news-item-noimage h3:hover {
        cursor: pointer;
    }

    @media (max-width: 1150px) {
        .news-list {
            gap: 10px;
        }
    }

    @media (max-width: 992px) {
        .news-grid-container {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: repeat(3, auto);
        }

        .news-list {
            display: flex;
            flex-direction: row;
            gap: 40px;
        }

        .news-list>* {
            flex: 1 1 0;
            max-width: 33.33%;
        }

        .news-list .news-item {
            height: 100px;
        }

        .news-list .news-item img {
            display: none;
        }

        .news-list .news-item p {
            font-size: 15px;
        }

        .news-list .news-item-noimage p {
            font-size: 15px;
        }
    }
</style>

<section class="news-feature-grid" id="news-feature-grid">
    @if(isset($topContents) && count($topContents) >= 7)
    <div class="news-grid-container">
        <!-- Right column: big feature -->
        <div class="news-feature">
            <a href="{{ route('news.show', $topContents[0]->content->shortlink) }}">
                <img loading="lazy" decoding="async" src="{{ $topContents[0]->content->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                    alt="{{ $topContents[0]->content->title ?? '' }}">
            </a>
            <h3>
                <x-category-links :content="$topContents[0]->content" />
            </h3>
            <a href="{{ route('news.show', $topContents[0]->content->shortlink) }}"
                style="text-decoration: none; color: inherit;">
                <h2>{{ $topContents[0]->content->title ?? '' }}</h2>
            </a>
            <p class="article-desc">{{ $topContents[0]->content->summary ?? '' }}</p>
        </div>

        <!-- Left column: small news cards -->
        <div class="news-list">
            <div class="news-item">
                <a href="{{ route('news.show', $topContents[1]->content->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $topContents[1]->content->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                        alt="News 1">
                </a>
                <h3>
                    <x-category-links :content="$topContents[1]->content" />
                </h3>
                <a href="{{ route('news.show', $topContents[1]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[1]->content->title ?? '' }}</p>
                </a>
            </div>
            <div class="news-item">
                <a href="{{ route('news.show', $topContents[3]->content->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $topContents[3]->content->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                        alt="News 3">
                </a>
                <h3>
                    <x-category-links :content="$topContents[3]->content" />

                </h3>
                <a href="{{ route('news.show', $topContents[3]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[3]->content->title ?? '' }}</p>
                </a>
            </div>
            <div class="news-item-noimage">
                <h3>
                    <x-category-links :content="$topContents[5]->content" />

                </h3>
                <a href="{{ route('news.show', $topContents[5]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[5]->content->title ?? '' }}</p>
                </a>
            </div>
        </div>
        <!-- Left column: small news cards -->
        <div class="news-list">
            <div class="news-item">
                <a href="{{ route('news.show', $topContents[2]->content->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $topContents[2]->content->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                        alt="News 1">
                </a>
                <h3>
                    <x-category-links :content="$topContents[2]->content" />
                </h3>
                <a href="{{ route('news.show', $topContents[2]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[2]->content->title ?? '' }}</p>
                </a>
            </div>
            <div class="news-item">
                <a href="{{ route('news.show', $topContents[4]->content->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $topContents[4]->content->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                        alt="News 2">
                </a>
                <h3>
                    <x-category-links :content="$topContents[4]->content" />
                </h3>
                <a href="{{ route('news.show', $topContents[4]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[4]->content->title ?? '' }}</p>
                </a>
            </div>
            <div class="news-item-noimage">
                <h3>
                    <x-category-links :content="$topContents[6]->content" />
                </h3>
                <a href="{{ route('news.show', $topContents[6]->content->shortlink) }}"
                    style="text-decoration: none; color: inherit;">
                    <p>{{ $topContents[6]->content->title ?? '' }}</p>
                </a>
            </div>
        </div>

    </div>
    @endif
</section>
