<style>
    .algeria-grid-container {
        display: grid;
        grid-template-columns: 8fr 4fr;
        /* Right: big, Left: list */
        gap: 20px;
    }

    .algeria-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .algeria-feature {
        position: relative;
    }

    .algeria-feature img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    .algeria-feature h2 {
        font-size: 24px;
        margin: 0px 0px 8px 0;
        font-family: asswat-bold;
        line-height: 1.4;
    }

    .algeria-feature h3 {
        font-family: asswat-light;
        font-weight: lighter;
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
    }

    .algeria-feature p {
        font-size: 17px;
        color: #555;
        line-height: 1.5;
    }

    .algeria-feature-m img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    .algeria-feature-m h2 {
        font-size: 18px;
        margin: 0px 0px 8px 0;
        font-family: asswat-bold;
        line-height: 1.4;
    }

    .algeria-feature-m h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .algeria-feature-m p {
        font-size: 14px;
        color: #555;
        line-height: 1.5;
    }

    .news-card-horizontal {
        display: flex;
        align-items: center;
        gap: 10px;
        direction: rtl;
        margin-bottom: 10px;

        /* Image right, text left for Arabic */
    }

    .news-card-horizontal .news-card-image img {
        width: 140px;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
    }

    .news-card-horizontal .news-card-text {
        flex: 1;
    }

    .news-card-horizontal .news-card-text h3 {
        font-size: 12px;
        margin: 0 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .news-card-horizontal .news-card-text p {
        font-size: 14px;
        margin: 0;
        font-family: asswat-medium;
        line-height: 1.4;
    }

    .algeria-extra-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #f5f5f5;
        margin-top: 30px;
    }

    .extra-item {
        position: relative;
        padding: 15px;
    }

    .extra-item h3 {
        font-size: 12px;
        color: #74747C;
        margin: 0 0 5px;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .extra-item p {
        font-size: 16px;
        font-family: asswat-medium;
        line-height: 1.5;
        margin: 0;
    }

    /* .extra-item:not(:first-child)::after {
        content: "";
        position: absolute;
        top: 15%;
        right: 0;
        height: 70%;
        width: 1px;
        background: #ccc;
    } */


    /* Cursor pointer for categories (h3) */
    .algeria-feature h3,
    .algeria-feature-m h3,
    .news-card-horizontal .news-card-text h3,
    .extra-item h3 {
        cursor: pointer;
    }

    /* Cursor pointer + underline on hover for titles (h2) */
    .algeria-feature h2:hover,
    .algeria-feature-m h2:hover,
    .news-card-horizontal .news-card-text p:hover,
    .extra-item p:hover,
    .extra-item h3:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* Underline on hover when the wrapping <a> is hovered (overrides inline text-decoration: none) */
    .algeria-feature a:hover h2,
    .algeria-feature-m a:hover h2,
    .news-card-horizontal a:hover p,
    .extra-item a:hover p {
        text-decoration: underline !important;
        cursor: pointer;
    }

    @media (max-width: 992px) {
        .algeria-grid-container {
            grid-template-columns: 1fr;
        }

        .algeria-grid-container .algeria-list {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .algeria-grid-container .algeria-list-div {
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* Add space between the cards */
        }

        .algeria-grid-container .algeria-list-div .news-card-horizontal {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .algeria-grid-container .algeria-list-div .news-card-horizontal img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .algeria-grid-container .algeria-list-div .news-card-text p {
            font-weight: bold;
        }

    }
</style>



<section class="algeria-feature-grid">
    <div class="algeria-grid-container">
        @if (isset($algeria) && count($algeria) >= 4)
            <div class="algeria-feature">
                <a href="{{ route('news.show', $algeria[0]->shortlink) }}">
                    <img loading="lazy" decoding="async" src="{{ $algeria[0]->media()->wherePivot('type', 'main')->first()->path }}" alt="Feature algeria">
                </a>
                <h3>
                    <x-category-links :content="$algeria[0]" />
                </h3>
                <a href="{{ route('news.show', $algeria[0]->shortlink) }}" style="text-decoration: none; color: inherit;">
                    <h2>{{ $algeria[0]->title }}</h2>
                </a>
                <p class="article-desc">{{ $algeria[0]->summary }}</p>
            </div>

            <div class="algeria-list">
                <div class="algeria-feature-m">
                    <a href="{{ route('news.show', $algeria[1]->shortlink) }}">
                        <img loading="lazy" decoding="async" src="{{ $algeria[1]->media()->wherePivot('type', 'main')->first()->path }}"
                            alt="Feature algeria small">
                    </a>
                    <h3>
                        <x-category-links :content="$algeria[1]" />

                    </h3>
                    <a href="{{ route('news.show', $algeria[1]->shortlink) }}"
                        style="text-decoration: none; color: inherit;">
                        <h2>{{ $algeria[1]->title }}</h2>
                    </a>
                    <p class="article-desc">{{ $algeria[1]->summary }}</p>
                </div>

                <div class="algeria-list-div">
                    <div class="news-card-horizontal">
                        <div class="news-card-image">
                            <a href="{{ route('news.show', $algeria[2]->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $algeria[2]->media()->wherePivot('type', 'main')->first()->path }}"
                                    alt="{{ $algeria[2]->title }}">
                            </a>
                        </div>
                        <div class="news-card-text">
                            <h3>
                                <x-category-links :content="$algeria[2]" />

                            </h3>
                            <a href="{{ route('news.show', $algeria[2]->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $algeria[2]->title }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="news-card-horizontal">
                        <div class="news-card-image">
                            <a href="{{ route('news.show', $algeria[3]->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $algeria[3]->media()->wherePivot('type', 'main')->first()->path }}"
                                    alt="{{ $algeria[3]->title }}">
                            </a>
                        </div>
                        <div class="news-card-text">
                            <h3>
                                <x-category-links :content="$algeria[3]" />

                            </h3>
                            <a href="{{ route('news.show', $algeria[3]->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $algeria[3]->title }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Extra Titles Grid -->
    <div class="algeria-extra-grid">
        @if (isset($algeriaLatestImportant) && count($algeriaLatestImportant) > 3)
            @foreach ($algeriaLatestImportant as $item)
                <div class="extra-item">
                    <h3>{{ $item->category?->name ?? '' }}</h3>
                    <a href="{{ route('news.show', $item->shortlink) }}" style="text-decoration: none; color: inherit;">
                        <p>{{ $item->title }}</p>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</section>



</section>
