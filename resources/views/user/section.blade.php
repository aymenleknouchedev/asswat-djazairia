@extends('layouts.index')

@section('title', !empty($sectionDescription) ? $sectionDescription : 'أصوات جزائرية | ' . ($arabicName ?? 'القسم'))

@section('meta_og_title', $arabicName ?? 'أصوات جزائرية')
@section('meta_twitter_title', $arabicName ?? 'أصوات جزائرية')

@if (!empty($sectionSocialImage))
    @section('meta_og_image', asset($sectionSocialImage))
    @section('meta_twitter_image', asset($sectionSocialImage))
@endif

@if (!empty($sectionDescription))
    @section('meta_description', $sectionDescription)
    @section('meta_og_description', $sectionDescription)
    @section('meta_twitter_description', $sectionDescription)
@endif

@section('content')

    <style>
        .web {
            display: block !important;
        }

        .mobile {
            display: none !important;
        }

        .newCategory {
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 20px;
        }

        .newCategory-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .newCategory-feature {
            position: relative;
        }

        .newCategory-feature img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            display: block;
        }

        .newCategory-feature h2 {
            font-size: 24px;
            margin: 0px 0px 8px 0;
            font-family: asswat-bold;
            line-height: 1.4;
        }

        .newCategory-feature h3 {
            font-family: asswat-light;
            font-weight: lighter;
            font-size: 12px;
            margin: 8px 0 4px;
            color: #74747C;
        }

        .newCategory-feature p {
            font-size: 17px;
            color: #555;
            line-height: 1.5;
        }

        .newCategory-feature-m img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            display: block;
        }

        .newCategory-feature-m h2 {
            font-size: 18px;
            margin: 0px 0px 8px 0;
            font-family: asswat-bold;
            line-height: 1.4;
        }

        .newCategory-feature-m h3 {
            font-size: 12px;
            margin: 8px 0 4px;
            color: #74747C;
            font-family: asswat-light;
            font-weight: lighter;
        }

        .newCategory-feature-m p {
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

        .newCategory-all-section {
            display: grid;
            grid-template-columns: 8fr 4fr;
            gap: 70px;
        }

        .newCategory-all-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .newCategory-all-card {
            display: flex;
            gap: 20px;
            direction: rtl;
            /* Image right, text left for Arabic */
        }

        .newCategory-all-card-image img {
            width: 300px;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .newCategory-all-card-text {
            flex: 1;
        }

        .newCategory-all-card-text h3 {
            font-family: asswat-light;
            font-size: 12px;
            color: #74747C;
            margin: 0 0 4px 0;
        }

        .newCategory-all-card-text h2 {
            font-family: asswat-bold;
            font-size: 18px;
            margin: 0 0 8px 0;
            line-height: 1.4;
        }

        .newCategory-all-card-text p {
            font-size: 15px;
            color: #555;
            line-height: 1.5;
            margin: 0;
        }

        /* .newCategory-all-side {} */

        .newCategoryReadMore {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .newCategoryReadMore-list {
            margin-top: 12px;
            display: flex;
            align-items: center;
            direction: rtl;
            font-family: asswat-bold;
            border-bottom: 1px solid #ddd;
            /* الخط الرمادي */
            padding-bottom: 10px;
            /* مسافة بين النص والخط */
        }

        .newCategoryReadMore-list:last-child {
            border-bottom: none;
        }

        .newCategoryReadMore-list .number {
            font-size: 32px;
            color: #e7e7e7;
            margin-left: 10px;
            font-weight: bold;
        }

        .newCategoryReadMore-list p {
            font-size: 16px;
            color: #333;
            line-height: 1.4;
        }

        .photos-load-more-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 12px 0;
            margin: 60px auto;
            background: #f5f5f5;
            color: #000;
            font-family: asswat-medium;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: .3s ease;
        }

        .photos-load-more-btn:hover {
            background: #ddd;
        }

        /* Make all text elements clickable except description paragraphs */

        /* عناوين العناصر الكبيرة والصغيرة */
        .newCategory-feature h2,
        .newCategory-feature h3,
        .newCategory-feature-m h2,
        .newCategory-feature-m h3,
        .news-card-horizontal .news-card-text h3,
        .news-card-horizontal .news-card-text p,
        .newCategory-all-card-text h2,
        .newCategory-all-card-text h3,
        .newCategoryReadMore-list p {
            cursor: pointer;
        }

        /* القوائم الجانبية */
        .newCategoryReadMore-list {
            cursor: pointer;
        }

        /* بطاقات المقترحات */
        .news-card-horizontal {
            cursor: pointer;
        }

        /* لا تضع cursor pointer للوصف الرئيسي */
        .newCategory-feature p,
        .newCategory-feature-m p,
        .newCategory-all-card-text p {
            cursor: auto;
        }

        /* Add underline on hover for big titles */
        .newCategory-feature h2:hover,
        .newCategory-feature-m h2:hover,
        .newCategory-all-card-text h2:hover,
        .newCategoryReadMore-list p:hover,
        .news-card-horizontal .news-card-text p:hover {
            text-decoration: underline;
        }

        .art-section-hero {
            position: relative;
            margin-top: 60px;
            background: linear-gradient(rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.851)),
                url('{{ asset($window->image ?? '') }}') center/cover no-repeat;
            color: #fff;
            direction: rtl;
            overflow: hidden;
        }

        .art-section-overlay {
            position: relative;
            /* makes sure content is on top of gradient */
            padding: 150px 20px 20px 20px;
            z-index: 1;
        }

        .art-section-title {
            text-align: right;
            font-size: 24px;
            margin-bottom: 24px;
            cursor: pointer;
        }

        .art-section-title:hover {
            text-decoration: underline;
        }

        .section-title {
            font-size: 32px;
        }

        .art-section-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .art-section-card {
            z-index: 1;
            /* cards are above gradient background */
        }

        .art-section-card img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
            border: 1px solid white;
        }

        .art-section-card h2,
        .art-section-card a h2 {
            margin-top: 5px;
            font-size: 15px;
            cursor: pointer;
            font-family: 'asswat-medium' !important;
        }

        .art-section-card h2:hover {
            margin-top: 5px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>

    <style>
        @media (max-width: 992px) {
            .web {
                display: none !important;
            }

            .mobile {
                display: block !important;
            }

            /* Horizontal snap scroller (reuse styles from home) */
            .mobile-h-wrapper {
                width: 100%;
                height: 80svh;
                scroll-snap-align: start;
                scroll-snap-stop: always;
                position: relative;
            }

            /* Full-screen wrapper for list sections */
            .mobile-fullscreen-wrapper {
                width: 100%;
                height: 100vh;
                scroll-snap-align: start;
                scroll-snap-stop: always;
                position: relative;
            }

            .h-snap {
                height: 100%;
                width: 100%;
                display: grid;
                grid-auto-flow: column;
                grid-auto-columns: 100vw;
                overflow-x: auto;
                overflow-y: hidden;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                overscroll-behavior-x: contain;
                scrollbar-width: none;
                direction: rtl;
            }

            .h-snap::-webkit-scrollbar {
                display: none;
            }

            .h-snap-slide {
                width: 100vw;
                height: 100%;
                scroll-snap-align: start;
                scroll-snap-stop: always;
                /* ensure one-slide-at-a-time */
                position: relative;
                display: block;
                text-decoration: none;
                color: inherit;
            }

            .post-overlay-dark {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.0));
                z-index: 1;
            }

            .featured-post-section-badge {
                position: absolute;
                top: 90px;
                right: 16px;
                z-index: 2;
                background: #fff;
                color: #000;
                padding: 6px 12px;
                font-size: 23px;
                font-weight: 800;
                line-height: 1;
                border-radius: 0;
                display: inline-block;
                text-align: center;
            }

            .section-fixed-ui {
                position: absolute;
                top: 90px;
                right: 16px;
                z-index: 3;
                display: flex;
                align-items: stretch;
                gap: 8px;
            }

            .section-fixed-ui .featured-post-section-badge {
                position: static;
            }

            .h-indicators {
                display: flex;
                flex-direction: row;
                align-items: stretch;
                justify-content: flex-start;
                gap: 5px;
            }

            .h-indicator {
                width: 4px;
                height: 100%;
                background: rgba(255, 255, 255, 0.262);
                border-radius: 2px;
            }

            .h-indicator.active {
                background: #ffffff;
            }

            .mobile-featured-post {
                width: 100%;
                height: 80svh;
                position: relative;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                overflow: hidden;
            }

            .featured-post-content2 {
                position: absolute;
                bottom: 60px;
                left: 0;
                right: 0;
                z-index: 2;
                padding: 0 20px;
                color: #fff;
                direction: rtl;
                text-align: right;
            }

            .featured-post-category-name {
                margin: 0 0 14px 0;
                font-size: 22px;
                color: #ffffff;
                letter-spacing: 1px;
            }

            .featured-post-title {
                margin: 0 0 15px 0;
                font-size: 32px;
                font-weight: 900;
                line-height: 1.4;
                color: #fff;
                font-family: 'asswat-bold';
            }

            .featured-post-description {
                margin: 0;
                font-size: 16px;
                color: #ffffff;
                line-height: 1.5;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                font-family: 'asswat-regular';
            }

            /* Simple list under scroller */
            .mobile-simple-list {
                padding: 12px 0 8px;
            }

            .mobile-simple-header {
                padding: 12px 16px 8px;
                font-size: 28px;
                font-weight: 800;
            }

            .mobile-simple-ul {
                list-style: none;
                margin: 0;
                padding: 0 16px 12px;
            }

            .mobile-simple-item+.mobile-simple-item {
                border-top: 1px solid rgba(0, 0, 0, 0.12);
            }

            .mobile-simple-link {
                display: flex;
                gap: 12px;
                align-items: center;
                padding: 12px 0;
                text-decoration: none;
                color: inherit;
            }

            .ms-thumb img {
                width: 135px;
                aspect-ratio: 16/9;
                object-fit: cover;
                display: block;
            }

            .ms-text {
                display: flex;
                flex-direction: column;
                /* occupy remaining space next to the 120px thumbnail + 12px gap */
                flex: 1 1 0;
                min-width: 0;
                /* allow text to shrink without overflow */
                width: calc(100% - 20px);

            }

            .ms-cat {
                margin: 0 0 6px 0;
                font-size: 14px;
                color: #74747C;
            }

            .ms-title {
                margin: 0;
                font-size: 18px;
                font-weight: 800;
                line-height: 1.35;
                color: #000;
                font-family: 'asswat-bold';
            }

            /* Separate styling for "المزيد من" posts */
            .mobile-more-link {
                display: flex;
                flex-direction: column;
                padding: 12px 0;
                text-decoration: none;
                color: inherit;
            }

            .mobile-more-link .ms-thumb {
                width: 100%;
            }

            .mobile-more-link .ms-thumb img {
                width: 100%;
                aspect-ratio: 16/9;
                object-fit: cover;
                display: block;
            }

            .mobile-more-link .ms-text {
                width: 100%;
                padding: 8px 0 0 0;
            }

            /* Mobile "Read more" button */
            #mobile-load-more-container {
                padding: 0px 0 28px;
            }

            #mobile-load-more-container .mobile-load-more-btn {
                display: block;
                width: 90%;
                max-width: 400px;
                margin: 20px auto;
                padding: 12px 24px;
                background: #f5f5f5;
                color: #000;
                font-family: asswat-medium;
                font-size: 16px;
                border: none;
                cursor: pointer;
                transition: .3s ease;
                text-align: center;
            }

            #mobile-load-more-container .mobile-load-more-btn:hover {
                background: #ddd;
            }

            /* Most Reads full-screen section */
            .most-reads-screen {
                width: 100%;
                min-height: 100dvh;
                background: #ffffff;
                color: #000;
                display: flex;
                flex-direction: column;
                padding: 131px 20px 0;
                box-sizing: border-box;
            }

            
            .most-reads-list {
                list-style: none;
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: 820px;
                display: flex;
                flex-direction: column;
                gap: 0;
                flex: 1;
                align-self: center;
            }

            .most-reads-item {
                display: flex;
                align-items: center;
                gap: 21px;
                padding: 24px 0;
            }

            .most-reads-item+.most-reads-item {
                border-top: 1px solid rgba(0, 0, 0, 0.12);
            }

            .mr-index {
                min-width: 28px;
                text-align: center;
                font-weight: 900;
                font-size: 43px;
                line-height: 1;
                color: #e7e7e7;
                font-family: 'asswat-bold';
            }

            .mr-title {
                display: inline-block;
                color: #fff;
                text-decoration: none;
                font-size: 20px;
                font-weight: 800;
                line-height: 1.4;
                font-family: 'asswat-bold';
            }

            /* Readmore Section with Grey Background */
            .readmore-section {
                width: 100%;
                background: #ffffff;
                color: #000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0px 20px;
                margin-bottom: 40px;
                box-sizing: border-box;
            }

            .readmore-list {
                list-style: none;
                margin: 0;
                padding: 0;
                width: 100%;
                max-width: 820px;
                display: flex;
                flex-direction: column;
                gap: 0;
            }

            .readmore-item {
                display: flex;
                align-items: center;
                gap: 21px;
                padding: 24px 0;
            }

            .readmore-item+.readmore-item {
                border-top: 1px solid rgba(0, 0, 0, 0.12);
            }

            .readmore-index {
                min-width: 28px;
                text-align: center;
                font-weight: 900;
                font-size: 43px;
                line-height: 1;
                color: #e7e7e7;
                font-family: 'asswat-bold';
            }

            .readmore-title {
                display: inline-block;
                color: #000;
                text-decoration: none;
                font-size: 20px;
                font-weight: 800;
                line-height: 1.4;
                font-family: 'asswat-bold';
            }

            .readmore-title:hover {
                text-decoration: underline;
            }

            /* Mobile Section Featured Post */
            .mobile-section-featured {
                padding: 0px 16px 0;
            }

            .mobile-section-featured-image img {
                width: 100%;
                aspect-ratio: 16 / 9;
                object-fit: cover;
                display: block;
            }

            .mobile-section-featured-content {
                padding: 12px 0;
            }

            .mobile-section-featured-cat {
                font-size: 14px;
                color: #74747C;
                margin: 0 0 8px 0;
                font-family: 'asswat-light';
            }

            .mobile-section-featured-title {
                font-size: 22px;
                font-weight: 800;
                line-height: 1.4;
                margin: 0;
                color: #000;
                font-family: 'asswat-bold';
            }

            /* Greybar hide on scroll */
            #greybar {
                transition: transform 0.3s ease, opacity 0.3s ease;
            }

            #greybar.hide {
                transform: translateY(-100%);
                opacity: 0;
            }
        }
    </style>

    <div class="web">
        @include('user.components.fixed-nav')

        <div class="container">
            <div class="title">
                <p class="section-title">{{ $arabicName ?? 'القسم' }}</p>
                @include('user.components.ligne')
                <div class="under-title-ligne-space"></div>
            </div>

            @if (isset($contents) && count($contents) > 0)
                <div class="newCategory">
                    <!-- Right: big feature -->
                    <div class="newCategory-feature">
                        @if (isset($contents[0]))
                            <a href="{{ route('news.show', $contents[0]->shortlink ?? '') }}">
                                <img loading="lazy" decoding="async" src="{{ $contents[0]->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                    alt="{{ $contents[0]->title ?? 'عنوان الخبر' }}">
                            </a>
                            <h3>
                                <x-category-links :content="$contents[0]" />
                            </h3>
                            <a href="{{ route('news.show', $contents[0]->shortlink ?? '') }}"
                                style="text-decoration: none; color: inherit;">
                                <h2>{{ $contents[0]->title ?? 'عنوان الخبر' }}</h2>
                            </a>
                            <p class="article-desc">{{ $contents[0]->summary ?? 'ملخص الخبر' }}</p>
                        @else
                            <img loading="lazy" decoding="async" src="./user/assets/images/placeholder.jpg" alt="لا يوجد محتوى">
                            <h3>لا يوجد محتوى</h3>
                            <h2>لا توجد أخبار متاحة</h2>
                            <p>لم يتم العثور على أي محتوى لهذا القسم.</p>
                        @endif
                    </div>

                    <!-- Left: list -->
                    <div class="newCategory-list">
                        @if (isset($contents[1]))
                            <div class="newCategory-feature-m">
                                <a href="{{ route('news.show', $contents[1]->shortlink ?? '') }}">
                                    <img loading="lazy" decoding="async" src="{{ $contents[1]->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                        alt="{{ $contents[1]->title ?? 'عنوان الخبر' }}">
                                </a>
                                <h3>
                                    <x-category-links :content="$contents[1]" />
                                </h3>
                                <a href="{{ route('news.show', $contents[1]->shortlink ?? '') }}"
                                    style="text-decoration: none; color: inherit;">
                                    <h2>{{ $contents[1]->title ?? 'عنوان الخبر' }}</h2>
                                </a>
                                <p class="article-desc">{{ $contents[1]->summary ?? 'ملخص الخبر' }}</p>
                            </div>
                        @endif

                        <div class="newCategory-list-div">
                            @for ($i = 2; $i <= 3; $i++)
                                @if (isset($contents[$i]))
                                    <div class="news-card-horizontal">
                                        <div class="news-card-image">
                                            <a href="{{ route('news.show', $contents[$i]->shortlink ?? '') }}">
                                                <img loading="lazy" decoding="async" src="{{ $contents[$i]->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                                    alt="{{ $contents[$i]->title ?? 'عنوان الخبر' }}">
                                            </a>
                                        </div>
                                        <div class="news-card-text">
                                            <h3>
                                                <x-category-links :content="$contents[$i]" />
                                            </h3>
                                            <a href="{{ route('news.show', $contents[$i]->shortlink ?? '') }}"
                                                style="text-decoration: none; color: inherit;">
                                                <p>{{ $contents[$i]->title ?? 'عنوان الخبر' }}</p>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <p>لا توجد أخبار متاحة في هذا القسم حالياً.</p>
                </div>
            @endif

            @if (isset($window) &&
                    isset($windowmanagement) &&
                    $windowmanagement->status == 1 &&
                    isset($window->contents) &&
                    count($window->contents) > 0)
                    <section class="art-section-hero">
                        <div class="art-section-overlay">
                            <a href="{{ route('window.show', $window->id) }}" style="text-decoration: none; color: inherit;">
                                <h2 class="art-section-title">{{ $window->name ?? 'النافذة' }}</h2>
                            </a>
                            <div class="art-section-grid">
                                @foreach ($window->contents->sortByDesc('published_date')->take(4) as $content)
                                    <div class="art-section-card">
                                        <a href="{{ route('news.show', $content->shortlink ?? '') }}">
                                            <img loading="lazy" decoding="async" src="{{ $content->media()->wherePivot('type', 'main')->first()->path ?? ($content->image ?? './user/assets/images/placeholder.jpg') }}"
                                                alt="{{ $content->title ?? 'عنوان الخبر' }}">
                                        </a>
                                        <a  href="{{ route('news.show', $content->shortlink ?? '') }}"
                                            style="text-decoration: none; color: inherit;">
                                            <h2 style="margin-top: 5px; font-size: 15px; cursor: pointer; font-family: 'asswat-bold'; font-weight: 600;">{{ $content->title ?? 'عنوان الخبر' }}</h2>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
            @endif

            @include('user.components.sp60')

            <div class="newCategory-all-section">
                <!-- Left: Cards loop -->
                <div class="newCategory-all-list">
                    @if (isset($moreContents) && count($moreContents) > 0)
                        <div id="content-container">
                            @include('user.partials.section-items', ['moreContents' => $moreContents])
                        </div>

                        @if (count($moreContents) >= 10)
                            <div class="text-center mt-3" id="load-more-container">
                                <button class="photos-load-more-btn btn btn-primary" data-page="1"
                                    data-section="{{ $section ?? '' }}">
                                    المزيد
                                </button>
                            </div>
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <p>لا توجد المزيد من الأخبار في هذا القسم.</p>
                        </div>
                    @endif
                </div>

                <!-- Right: Sidebar -->
                <div class="newCategory-all-side">
                    <div>
                        <p class="section-title">الأكثر قراءة</p>
                        @include('user.components.ligne')
                        <div class="newCategoryReadMore">
                            @if (isset($topViewed) && count($topViewed) > 0)
                                @foreach ($topViewed as $index => $item)
                                    <div class="newCategoryReadMore-list">
                                        <span class="number">{{ $index + 1 }}</span>
                                        <a href="{{ route('news.show', $item->shortlink ?? '') }}"
                                            style="text-decoration: none; color: inherit;">
                                            <p>{{ $item->title ?? 'عنوان الخبر' }}</p>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <p>لا توجد أخبار في قائمة الأكثر قراءة.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @include('user.components.sp60')

                    <p class="section-title">مقترحات</p>
                    @include('user.components.ligne')

                    @if (isset($suggestions) && count($suggestions) > 0)
                        @foreach ($suggestions as $content)
                            <div class="sp20" style="margin-top: 16px;"></div>
                            <div class="news-card-horizontal">
                                <div class="news-card-image">
                                    <a href="{{ route('news.show', $content->shortlink ?? '') }}">
                                        <img loading="lazy" decoding="async" src="{{ $content->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                            alt="{{ $content->title ?? 'تحلية مياه البحر' }}">
                                    </a>
                                </div>
                                <div class="news-card-text">
                                    <h3><x-category-links :content="$content" fallback="اقتصاد جزائري" /></h3>
                                    <a href="{{ route('news.show', $content->shortlink ?? '') }}"
                                        style="text-decoration: none; color: inherit;">
                                        <p>{{ $content->title ?? 'عنوان الخبر' }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <p>لا توجد اقتراحات متاحة حالياً.</p>
                        </div>
                    @endif
                </div>
            </div>

            @include('user.components.sp60')
        </div>

        @include('user.components.footer')
    </div>

    <div class="mobile">

        @include('user.mobile.mobile-home')

        <!-- Grey navigation bar -->
        <div id="greybar"
            style="background-color: #252525; height: 68px; position: fixed; top: 0; left: 0; right: 0; z-index: 10;">
        </div>

        <!-- Mobile simple section posts -->
        <div class="mobile-flow">
            <!-- Featured Post with 19/6 aspect ratio -->
            @if (isset($contents) && count($contents) > 0)
                <div class="mobile-container" style="margin-top: 68px;">
                    <div class="mobile-simple-list" dir="rtl">
                        <h2 class="mobile-simple-header">{{ $arabicName ?? 'القسم' }}</h2>
                        <div style="padding: 0px 16px">
                            @include('user.components.ligne')
                        </div>
                    </div>
                    <div class="mobile-section-featured" dir="rtl">
                        @php
                            $featuredPost = $contents[0];
                        @endphp
                        <a href="{{ route('news.show', $featuredPost->shortlink ?? '') }}"
                            style="text-decoration: none; color: inherit;">
                            <div class="mobile-section-featured-image">
                                <img loading="lazy" decoding="async" src="{{ $featuredPost->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                    alt="{{ $featuredPost->title ?? 'عنوان الخبر' }}">
                            </div>
                            <div class="mobile-section-featured-content">
                                @if (isset($featuredPost->category) && $featuredPost->category)
                                    <p class="mobile-section-featured-cat">{{ $featuredPost->category->name }}</p>
                                @endif
                                <h1 class="mobile-section-featured-title">{{ $featuredPost->title ?? 'عنوان الخبر' }}</h1>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 3 Small Posts -->
                <div class="mobile-container">
                    <div class="mobile-simple-list" dir="rtl">
                        <ul class="mobile-simple-ul" role="list" style="padding-top: 0;">
                            @for ($i = 1; $i <= 3; $i++)
                                @if (isset($contents[$i]))
                                    <li class="mobile-simple-item">
                                        <a class="mobile-simple-link"
                                            href="{{ route('news.show', $contents[$i]->shortlink) }}"
                                            aria-label="{{ $contents[$i]->title }}">
                                            <div class="ms-thumb">
                                                <img loading="lazy" decoding="async" src="{{ $contents[$i]->media()->wherePivot('type', 'main')->first()->path ?? asset($contents[$i]->image ?? 'user/assets/images/default-post.jpg') }}"
                                                    alt="{{ $contents[$i]->title }}">
                                            </div>
                                            <div class="ms-text">
                                                @if (isset($contents[$i]->category) && $contents[$i]->category)
                                                    <p class="ms-cat">{{ $contents[$i]->category->name }}</p>
                                                @endif
                                                <p class="ms-title">{{ $contents[$i]->mobile_title }}</p>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endfor
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Simple moreContents list -->
            @php
                $moreMobItems = [];
                if (isset($moreContents)) {
                    // Support paginator, collection, or array
                    if (
                        $moreContents instanceof \Illuminate\Pagination\LengthAwarePaginator ||
                        $moreContents instanceof \Illuminate\Contracts\Pagination\Paginator
                    ) {
                        $col = $moreContents->getCollection();
                    } else {
                        $col = $moreContents;
                    }
                    if ($col instanceof \Illuminate\Support\Collection) {
                        $moreMobItems = $col->take(8);
                    } elseif (is_array($col)) {
                        $moreMobItems = array_slice($col, 0, 8);
                    }
                }
            @endphp
            @if (!empty($moreMobItems))
                <div class="mobile-container">
                    <div class="mobile-simple-list" dir="rtl">
                        <ul class="mobile-simple-ul" role="list" id="mobile-section-list">
                            @foreach ($moreMobItems as $item)
                                @include('user.mobile.item')
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Mobile Read More button -->
            @if (isset($moreContents) && count($moreContents) >= 10)
                <div class="text-center mt-3" id="mobile-load-more-container">
                    <button class="mobile-load-more-btn btn btn-primary" data-page="1" data-section="{{ $section ?? '' }}">
                        المزيد
                    </button>
                </div>
            @else
                <div style="height: 40px;"></div>
            @endif


            <!-- Readmore Section -->
            @if (isset($topViewed) && is_countable($topViewed) && $topViewed->count())
                <div class="readmore-section" dir="rtl">
                    <div style="width: 100%; max-width: 820px;">
                        <p class="section-title" style="font-size: 20px; font-family: 'asswat-bold'; color: #141414; text-align: right; margin: 0 0 10px 0;">
                            الأكثر قراءة</p>
                        @include('user.components.ligne')
                        <ol class="readmore-list" role="list">
                            @foreach ($topViewed->take(5) as $i => $content)
                                <li class="readmore-item">
                                    <span class="readmore-index" aria-hidden="true">{{ $i + 1 }}</span>
                                    <a class="readmore-title" href="{{ route('news.show', $content->shortlink) }}">
                                        {{ \Illuminate\Support\Str::limit($content->mobile_title ?? $content->title, 50) }}
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            <!-- Mobile Footer -->
            @include('user.mobile.footer')


        </div>
    </div>



    <script>
        // Initialize Greybar Hide on Scroll
        function initializeGreybarScroll() {
            const greybar = document.getElementById('greybar');
            if (!greybar) return;

            const footer = document.querySelector('footer');

            window.addEventListener('scroll', function() {
                const footerRect = footer.getBoundingClientRect();
                const greybarRect = greybar.getBoundingClientRect();

                // Hide greybar only when it's about to overlap with footer
                if (footerRect.top < greybarRect.bottom) {
                    greybar.classList.add('hide');
                } else {
                    greybar.classList.remove('hide');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth <= 992) {
                initializeGreybarScroll();
            }
        });

        // Mobile Read More: fetch and append items to the simple list
        (function() {
            let mobileLoading = false;
            document.addEventListener('click', async function(e) {
                if (!e.target.classList.contains('mobile-load-more-btn')) return;
                if (mobileLoading) return;
                const btn = e.target;
                const section = btn.getAttribute('data-section');
                let page = parseInt(btn.getAttribute('data-page') || '1', 10) + 1;
                if (!section) {
                    console.error('Section is not defined');
                    return;
                }
                mobileLoading = true;
                btn.disabled = true;
                btn.textContent = 'جاري التحميل...';
                try {
                    const url = `/api/section/${section}?page=${page}&view=mobile`;
                    const resp = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                    const trimmed = (await resp.text()).trim();
                    if (!trimmed) {
                        btn.closest('#mobile-load-more-container')?.remove();
                    } else {
                        let list = document.getElementById('mobile-section-list');
                        if (!list) {
                            // Fall back: pick the first mobile list inside the mobile section
                            list = document.querySelector('.mobile .mobile-simple-ul');
                        }
                        if (list) {
                            list.insertAdjacentHTML('beforeend', trimmed);
                        }
                        btn.setAttribute('data-page', String(page));
                        btn.disabled = false;
                        btn.textContent = 'المزيد';
                    }
                } catch (err) {
                    console.error('Mobile load more failed', err);
                    btn.disabled = false;
                    btn.textContent = 'المزيد';
                }
                mobileLoading = false;
            });
        })();
    </script>

@endsection

<script>
    let loading = false;

    document.addEventListener("click", async function(e) {
        if (e.target.classList.contains("photos-load-more-btn")) {
            if (loading) return;

            let btn = e.target;
            let page = parseInt(btn.getAttribute("data-page")) + 1;
            let section = btn.getAttribute("data-section");

            // Check if section is defined
            if (!section) {
                console.error("Section is not defined");
                alert("خطأ: لم يتم تحديد القسم");
                return;
            }

            console.log(`Loading more for section: ${section}, page: ${page}`);

            loading = true;
            btn.disabled = true;
            btn.textContent = "جاري التحميل...";

            try {
                const url = `/api/section/${section}?page=${page}`;
                console.log(`Fetching URL: ${url}`);

                let response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                console.log(`Response status: ${response.status}`);

                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
                }

                let data = await response.text();
                console.log(`Received data length: ${data.length}`);

                if (data.trim().length === 0) {
                    btn.closest("#load-more-container")?.remove();
                } else {
                    let container = document.querySelector("#content-container");
                    if (container) {
                        container.insertAdjacentHTML("beforeend", data);
                        btn.setAttribute("data-page", page);
                        btn.disabled = false;
                        btn.textContent = "المزيد";
                    }
                }
            } catch (error) {
                console.error("Error loading more content:", error);
                alert("خطأ في تحميل المزيد: " + error.message);
                btn.disabled = false;
                btn.textContent = "المزيد";
            }

            loading = false;
        }
    });
</script>




