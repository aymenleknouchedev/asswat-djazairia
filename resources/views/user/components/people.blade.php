<style>
    .people-grid-container {
        display: grid;
        grid-template-columns: 8fr 4fr;
        /* Right: big, Left: list */
        gap: 20px;
    }

    .people-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .people-feature {
        position: relative;
        background-color: #F5F5F5;
    }

    .people-feature img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    .people-feature .buttom-side {
        padding: 25px;
    }

    .people-feature h2 {
        font-size: 24px;
        margin: 0px 0px 8px 0;
        font-family: asswat-bold;
        line-height: 1.4;
    }

    .people-feature h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .people-feature p {
        font-size: 17px;
        color: #555;
        line-height: 1.5;
    }

    .people-feature-m img {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }

    .people-feature-m h2 {
        font-size: 18px;
        margin: 0px 0px 8px 0;
        font-family: asswat-bold;
        line-height: 1.4;
    }

    .people-feature-m h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .people-feature-m p {
        font-size: 14px;
        color: #555;
        line-height: 1.5;
    }

    .people-card-horizontal {
        display: flex;
        align-items: center;
        gap: 10px;
        direction: rtl;
        /* Image right, text left for Arabic */
    }

    .people-card-horizontal .people-card-image img {
        width: 140px;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
    }

    .people-card-horizontal .people-card-text {
        flex: 1;
    }

    .people-card-horizontal .people-card-text h3 {
        font-size: 12px;
        margin: 0 0 4px;
        color: #74747C;
    }

    .people-card-horizontal .people-card-text p {
        font-size: 14px;
        margin: 0;
        font-family: asswat-medium;
        line-height: 1.4;
    }

    /* Cursor pointer for people categories */
    .people-feature h3,
    .people-feature-m h3,
    .people-card-horizontal .people-card-text h3 {
        cursor: pointer;
    }

    /* Cursor pointer + underline on hover for people titles */
    .people-feature h2:hover,
    .people-feature-m h2:hover,
    .people-card-horizontal .people-card-text p:hover {
        text-decoration: underline;
        cursor: pointer;
    }
</style>



<section class="people-feature-grid">
    <div class="people-grid-container">
        <!-- Right: big feature -->
        <div class="people-feature">
            <a href="{{ route('news.show', $people[0]->shortlink) }}">
                <img loading="lazy" decoding="async" src="{{ $people[0]->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                    alt="{{ $people[0]->title ?? '' }}">
            </a>
            <div class="buttom-side">
                <h3>
                    <x-category-links :content="$people[0]" />

                </h3>
                <a href="{{ route('news.show', $people[0]->shortlink) }}" style="text-decoration: none; color: inherit;">
                    <h2>{{ $people[0]->title ?? '' }}</h2>
                </a>
                <p class="article-desc">{{ $people[0]->summary ?? '' }}</p>
            </div>
        </div>

        <!-- Left: list -->
        <div class="people-list">
            @foreach ($people->slice(1, 2) as $person)
                <div class="people-feature-m">
                    <a href="{{ route('news.show', $person->shortlink) }}">
                        <img loading="lazy" decoding="async" src="{{ $person->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                            alt="{{ $person->title ?? '' }}">
                    </a>
                    <h3>
                        <x-category-links :content="$person" />

                    </h3>
                    <a href="{{ route('news.show', $person->shortlink) }}" style="text-decoration: none; color: inherit;">
                        <h2>{{ $person->title ?? '' }}</h2>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
