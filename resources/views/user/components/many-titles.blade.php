<style>
    .many-titles-grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .many-titles-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }



    .many-titles-feature-m {
        height: 355px;
    }

    .many-titles-feature-m img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
    }

    .many-titles-feature-m h3 {
        font-size: 12px;
        margin: 8px 0 4px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .many-titles-feature-m h2 {
        font-size: 18px;
        margin: 0;
        font-family: asswat-bold;
        color: #333;
        line-height: 1.4;
    }

    .many-titles-feature-m .top {
        height: 291px;
    }



    .many-titles-feature-m p {
        font-size: 14px;
        margin-top: 8px;
        line-height: 1.5;
        color: #555;
    }

    .many-titles-card {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        direction: rtl;
    }

    .many-titles-card-image {
        flex-shrink: 0;
        width: 120px;
    }

    .many-titles-card-image img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
    }

    .many-titles-card-text span {
        font-size: 12px;
        color: #74747C;
        font-family: asswat-light;
        font-weight: lighter;
    }

    .many-titles-card-text p {
        font-size: 14px;
        margin: 0;
        line-height: 1.4;
        font-family: asswat-bold;
        color: #333;
    }

    /* Cursor pointer for many-titles categories */
    .many-titles-card-text span {
        cursor: pointer;
    }

    /* Cursor pointer + underline on hover for many-titles titles */
    .many-titles-card-text p:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* Cursor pointer + underline for main feature h2 titles */
    .many-titles-feature-m h2:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    /* Cursor pointer for main feature h3 categories */
    .many-titles-feature-m h3 {
        cursor: pointer;
    }

    @media (max-width: 1050px) {
        .many-titles-feature-m .top {
            height: 260px;
        }

        .many-titles-card-text p {
            font-size: 12px;
        }
    }
</style>

@php
    $manySections = [
        'technology' => ['تكنولوجيا'],
        'health' => ['صحة'],
        'environment' => ['بيئة'],
    ];
@endphp

<section class="many-titles-feature-grid">
    <div class="many-titles-grid-container">

        <!-- Column 1 -->
        <div>
            <p class="section-title"><a href="{{ route('newSection', ['section' => 'technology']) }}"
                    style="color: inherit; text-decoration: none;">تكنولوجيا</a></p>
            <div class="many-titles-column">
                @include('user.components.ligne')

                @if (isset($technology[0]))
                    <div class="many-titles-feature-m">
                        <div class="top">
                            <a href="{{ route('news.show', $technology[0]->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $technology[0]->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $technology[0]->title ?? '' }}">
                            </a>
                            <h3>
                                <x-category-links :content="$technology[0]" />

                            </h3>
                            <a href="{{ route('news.show', $technology[0]->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <h2>{{ $technology[0]->title ?? '' }}</h2>
                            </a>
                            <p class="article-desc">{{ $technology[0]->summary ?? '' }}</p>
                        </div>
                        {{-- <p>{{ $technology[0]->summary ?? '' }}</p> --}}
                    </div>
                @endif

                @foreach ($technology->slice(1, 2) as $item)
                    <div class="many-titles-card">
                        <div class="many-titles-card-image">
                            <a href="{{ route('news.show', $item->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $item->title ?? '' }}">
                            </a>
                        </div>
                        <div class="many-titles-card-text">
                            <span>
                                <x-category-links :content="$item" />

                            </span>
                            <a href="{{ route('news.show', $item->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $item->title ?? '' }}</p>
                            </a>
                            {{-- <p>{{ $item->summary ?? '' }}</p> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Column 2 -->
        <div>
            <p class="section-title">
                <a href="{{ route('newSection', ['section' => 'health']) }}"
                    style="color: inherit; text-decoration: none;">صحة</a>
            </p>
            <div class="many-titles-column">
                @include('user.components.ligne')

                @if (isset($health[0]))
                    <div class="many-titles-feature-m">
                        <div class="top">
                            <a href="{{ route('news.show', $health[0]->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $health[0]->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $health[0]->title ?? '' }}">
                            </a>
                            <h3>
                                <x-category-links :content="$health[0]" />

                            </h3>
                            <a href="{{ route('news.show', $health[0]->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <h2>{{ $health[0]->title ?? '' }}</h2>
                            </a>
                            <p class="article-desc">{{ $health[0]->summary ?? '' }}</p>
                        </div>
                        {{-- <p>{{ $health[0]->summary ?? '' }}</p> --}}
                    </div>
                @endif

                @foreach ($health->slice(1, 2) as $item)
                    <div class="many-titles-card">
                        <div class="many-titles-card-image">
                            <a href="{{ route('news.show', $item->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $item->title ?? '' }}">
                            </a>
                        </div>
                        <div class="many-titles-card-text">
                            <span>
                                <x-category-links :content="$item" />

                            </span>
                            <a href="{{ route('news.show', $item->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $item->title ?? '' }}</p>
                            </a>
                            {{-- <p>{{ $item->summary ?? '' }}</p> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Column 3 -->
        <div>
            <p class="section-title">
                <a href="{{ route('newSection', ['section' => 'environment']) }}"
                    style="color: inherit; text-decoration: none;">بيئة</a>
            </p>
            <div class="many-titles-column">
                @include('user.components.ligne')

                @if (isset($environment[0]))
                    <div class="many-titles-feature-m">
                        <div class="top">
                            <a href="{{ route('news.show', $environment[0]->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $environment[0]->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $environment[0]->title ?? '' }}">
                            </a>
                            <h3>
                                <x-category-links :content="$environment[0]" />

                            </h3>
                            <a href="{{ route('news.show', $environment[0]->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <h2>{{ $environment[0]->title ?? '' }}</h2>
                            </a>
                            <p class="article-desc">{{ $environment[0]->summary ?? '' }}</p>
                        </div>
                        {{-- <p>{{ $environment[0]->summary ?? '' }}</p> --}}
                    </div>
                @endif

                @foreach ($environment->slice(1, 2) as $item)
                    <div class="many-titles-card">
                        <div class="many-titles-card-image">
                            <a href="{{ route('news.show', $item->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                    alt="{{ $item->title ?? '' }}">
                            </a>
                        </div>
                        <div class="many-titles-card-text">
                            <span>
                                <x-category-links :content="$item" />

                            </span>
                            <a href="{{ route('news.show', $item->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $item->title ?? '' }}</p>
                            </a>
                            {{-- <p>{{ $item->summary ?? '' }}</p> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
