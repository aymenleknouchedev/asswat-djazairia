@extends('layouts.index')

@section('title', 'أصوات جزائرية | ' . ($theme->name ?? 'الأخبار'))

@section('content')

    <style>
        .web {
            display: block !important;
        }

        .mobile {
            display: none !important;
        }

        .section-title {
            font-size: 32px;
        }

        .newCategory-all-section {
            width: 100%;
        }

        .newCategory-all-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .newCategory-all-card {
            display: flex;
            flex-direction: column;
            direction: rtl;
        }

        .newCategory-all-card-image img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .newCategory-all-card-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px 0;
        }


        .newCategory-all-card-text h3 {
            font-family: asswat-regular !important;
            font-weight: normal !important;
            font-size: 16px !important;
            color: #7c7c74 !important;
            margin: 0 0 4px 0 !important;
        }

        .newCategory-all-card-text h2 {
            font-family: asswat-bold !important;
            font-weight: normal !important;
            font-size: 20px !important;
            color: #333 !important;
            margin: 0 0 4px 0 !important;
        }

        .newCategory-all-card-text p {
            font-size: 16px !important;
            line-height: 1.5 !important;
            color: #555 !important;
            margin: 0 !important;
        }



        /* Load more button */
        .category-load-more-btn {
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

        .category-load-more-btn:hover {
            background: #ddd;
        }

        /* Mobile simple header */
        .mobile-simple-header {
            padding: 12px 16px 8px;
            font-size: 28px;
            font-weight: 800;
            font-family: 'asswat-bold';
        }

        .mobile-simple-ul {
            list-style: none;
            margin: 0;
            padding: 0 16px 12px;
        }

        .mobile-simple-item+.mobile-simple-item {
            border-top: 1px solid rgba(0, 0, 0, 0.12);
        }

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
            display: flex;
            flex-direction: column;
            padding-top: 8px;
        }

        .ms-title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.35;
            color: #000;
            font-family: 'asswat-bold';
        }

        .mobile-load-more-btn {
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
        }

        .mobile-load-more-btn:hover {
            background: #ddd;
        }

        /* Greybar hide on scroll */
        #greybar {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #greybar.hide {
            transform: translateY(-100%);
            opacity: 0;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .newCategory-all-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .web {
                display: none !important;
            }

            .mobile {
                display: block !important;
            }

            .newCategory-all-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .newCategory-all-list {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="web">
        @include('user.components.fixed-nav')

        <div class="container">
            <div class="title">
                <p class="section-title">{{ $theme->name ?? 'الأخبار' }}</p>
                @include('user.components.ligne')
                <div class="under-title-ligne-space"></div>
            </div>

            <div class="newCategory-all-section">
                <div class="newCategory-all-list" id="category-container">
                    @foreach ($articles as $item)
                        <div class="newCategory-all-card">
                            <!-- Image -->
                            <div class="newCategory-all-card-image">
                                <a href="{{ route('news.show', $item->shortlink) }}">
                                    <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/IMG20.jpg' }}"
                                        alt="{{ $item->title }}">
                                </a>
                            </div>

                            <!-- Text -->
                            <div class="newCategory-all-card-text">
                                <h3>
                                    @if ($item->category && $item->country)
                                        <a
                                            href="{{ route('category.show', ['id' => $item->category->id, 'type' => 'Category']) }}">
                                            {{ $item->category->name ?? '' }}
                                        </a>
                                        -
                                        <a
                                            href="{{ route('category.show', ['id' => $item->country->id, 'type' => 'Country']) }}">
                                            {{ $item->country->name ?? '' }}
                                        </a>
                                    @elseif ($item->category && $item->continent)
                                        <a
                                            href="{{ route('category.show', ['id' => $item->category->id, 'type' => 'Category']) }}">
                                            {{ $item->category->name ?? '' }}
                                        </a>
                                        -
                                        <a
                                            href="{{ route('category.show', ['id' => $item->continent->id, 'type' => 'Continent']) }}">
                                            {{ $item->continent->name ?? '' }}
                                        </a>
                                    @elseif ($item->category)
                                        <a
                                            href="{{ route('category.show', ['id' => $item->category->id, 'type' => 'Category']) }}">
                                            {{ $item->category->name ?? '' }}
                                        </a>
                                    @endif
                                </h3>
                                <a href="{{ route('news.show', $item->shortlink) }}"
                                    style="text-decoration: none; color: inherit;">
                                    <h2>{{ $item->title }}</h2>
                                </a>
                                <p class="article-desc">{{ $item->summary }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($articles) >= 9)
                    <div class="text-center mt-3" id="load-more-container">
                        <button class="category-load-more-btn" data-page="1">المزيد</button>
                    </div>
                @else
                    <div style="height: 60px;"></div>
                @endif
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

        <!-- Mobile Tags Content -->
        <div class="mobile-flow">
            <div class="mobile-container" style="margin-top: 68px;">
                <div class="mobile-simple-list" dir="rtl">
                    <h2 class="mobile-simple-header">{{ $theme->name ?? 'الأخبار' }}</h2>
                    <div style="padding: 0px 16px">
                        @include('user.components.ligne')
                    </div>
                    <ul class="mobile-simple-ul" role="list" id="mobile-tags-container">
                        @foreach ($articles as $item)
                            @include('user.mobile.item')
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Mobile Load More button -->
            @if (count($articles) >= 9)
                <div class="text-center" id="mobile-load-more-container">
                    <button class="mobile-load-more-btn" data-page="1">المزيد</button>
                </div>
            @else
                <div style="height: 40px;"></div>
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

        let loading = false;
        const categoryId = @json($current_id ?? null);
        const categoryType = @json($type ?? '');

        document.addEventListener("click", async function(e) {
            // Desktop load more
            if (e.target.classList.contains("category-load-more-btn")) {
                if (loading) return;

                let btn = e.target;
                let page = parseInt(btn.getAttribute("data-page")) + 1;

                loading = true;
                btn.disabled = true;
                btn.textContent = "جاري التحميل...";

                try {
                    let response = await fetch(`/tag/${categoryId}?page=${page}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    if (!response.ok) throw new Error("خطأ في السيرفر");

                    let data = await response.text();

                    if (data.trim().length === 0) {
                        btn.closest("#load-more-container").remove();
                    } else {
                        document.getElementById("category-container").insertAdjacentHTML("beforeend", data);
                        btn.setAttribute("data-page", page);
                        btn.disabled = false;
                        btn.textContent = "المزيد";
                    }
                } catch (error) {
                    alert("خطأ في تحميل المزيد");
                    btn.disabled = false;
                    btn.textContent = "المزيد";
                }

                loading = false;
            }

            // Mobile load more
            if (e.target.classList.contains("mobile-load-more-btn")) {
                if (loading) return;

                let btn = e.target;
                let page = parseInt(btn.getAttribute("data-page")) + 1;

                loading = true;
                btn.disabled = true;
                btn.textContent = "جاري التحميل...";

                try {
                    let response = await fetch(`/tag/${categoryId}?page=${page}&view=mobile`, {
                        headers: { "X-Requested-With": "XMLHttpRequest" }
                    });

                    if (!response.ok) throw new Error("خطأ في السيرفر");

                    let data = await response.text();
                    const trimmed = data.trim();

                    if (trimmed.length === 0) {
                        const cont = btn.closest("#mobile-load-more-container");
                        if (cont) cont.remove();
                    } else {
                        const mobileContainer = document.getElementById("mobile-tags-container");
                        if (mobileContainer) {
                            mobileContainer.insertAdjacentHTML("beforeend", trimmed);
                        }

                        btn.setAttribute("data-page", page);
                        btn.disabled = false;
                        btn.textContent = "المزيد";
                    }
                } catch (error) {
                    alert("خطأ في تحميل المزيد");
                    btn.disabled = false;
                    btn.textContent = "المزيد";
                }

                loading = false;
            }
        });
    </script>
@endsection



