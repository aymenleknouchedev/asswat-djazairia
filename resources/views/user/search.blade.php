@extends('layouts.index')

@section('title', 'أصوات جزائرية | نتائج البحث')

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
            display: grid;
            grid-template-columns: 10fr 2fr;
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
            align-items: center;
            /* vertically center */
        }

        .newCategory-all-card-date {
            color: #333;
            /* dark grey */
            font-size: 14px;
            width: 125px;
            flex: 0 0 125px;
            text-align: right;
            white-space: nowrap;
        }

        .newCategory-all-card-date h4 {
            margin: 0;
            white-space: nowrap;
        }

        .newCategory-all-card-image img {
            width: 300px;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .newCategory-all-card-text {
            flex: 1;
            display: flex;
            flex-direction: column;
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
            color: #555 !important;
            line-height: 1.5 !important;
            margin: 0 !important;
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

        /* Greybar hide on scroll */
        #greybar {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #greybar.hide {
            transform: translateY(-100%);
            opacity: 0;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .newCategory-all-section {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .newCategory-all-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .newCategory-all-card-image img {
                width: 100%;
            }

            .newCategory-all-card-date {
                margin-bottom: 10px;
            }
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
        }
    </style>

    <div class="web">
        @include('user.components.fixed-nav')

        <div class="container">
            <div class="title">
                <p class="section-title">نتائج البحث عن: {{ $query }}</p>
                @include('user.components.ligne')
                <div class="under-title-ligne-space"></div>
            </div>

            <div class="newCategory-all-section">
                <!-- Left: نتائج البحث -->
                <div class="newCategory-all-list">
                    @forelse ($results as $item)
                        <div class="newCategory-all-card">
                            <!-- Date on the left -->
                            <div class="newCategory-all-card-date">
                                @php
                                    $months = [
                                        '01' => 'جانفي',
                                        '02' => 'فيفري',
                                        '03' => 'مارس',
                                        '04' => 'أفريل',
                                        '05' => 'ماي',
                                        '06' => 'جوان',
                                        '07' => 'جويلية',
                                        '08' => 'أوت',
                                        '09' => 'سبتمبر',
                                        '10' => 'أكتوبر',
                                        '11' => 'نوفمبر',
                                        '12' => 'ديسمبر',
                                    ];
                                    $date = $item->created_at;
                                    $day = $date->format('d');
                                    $month = $months[$date->format('m')];
                                    $year = $date->format('Y');
                                @endphp
                                <h4>{{ $day }} {{ $month }} {{ $year }}</h4>
                            </div>

                            <!-- Image -->
                            <div class="newCategory-all-card-image">
                                <a href="{{ route('news.show', $item->shortlink) }}">
                                    <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/IMG20.jpg' }}"
                                        alt="{{ $item->title }}">
                                </a>
                            </div>

                            <!-- Text -->
                            <div class="newCategory-all-card-text">
                                <h3><x-category-links :content="$item" /></h3>
                                <a href="{{ route('news.show', $item->shortlink) }}"
                                    style="text-decoration: none; color: inherit;">
                                    <h2>{{ $item->title }}</h2>
                                </a>
                                <p class="article-desc">{{ $item->summary }}</p>
                            </div>
                        </div>
                    @empty
                        <p>لا توجد نتائج مطابقة.</p>
                    @endforelse
                </div>

                <!-- Right: الأكثر قراءة -->
                <div class="newCategory-all-side">

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

        <!-- Mobile Search Results Content -->
        <div class="mobile-flow">
            <div class="mobile-container" style="margin-top: 68px;">
                <div class="mobile-simple-list" dir="rtl">
                    <h2 class="mobile-simple-header">نتائج البحث</h2>
                    <div style="padding: 0px 16px">
                        <p style="font-size: 14px; color: #666; margin: 0 0 12px 0;">البحث عن:
                            <strong>{{ $query }}</strong></p>
                    </div>
                    <ul class="mobile-simple-ul" role="list" id="mobile-search-container">
                        @forelse ($results as $item)
                            @include('user.mobile.item')
                        @empty
                            <li style="padding: 12px 0; text-align: center; color: #999;">لا توجد نتائج مطابقة.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Mobile Load More button -->
            @if (isset($totalResults) && isset($perPage) && $totalResults > count($results))
                <div class="text-center mt-3" id="mobile-search-load-more-container">
                    <button class="mobile-load-more-btn btn btn-primary" data-page="1">المزيد</button>
                </div>
            @else
                <div style="height: 40px;"></div>
            @endif

            <!-- Mobile Footer -->
            @include('user.mobile.footer')

        </div>

    </div>
@endsection

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

    // Mobile load more (search)
    let mobileSearchLoading = false;
    document.addEventListener("click", async function(e) {
        const btn = e.target.closest('#mobile-search-load-more-container .mobile-load-more-btn');
        if (!btn) return;
        if (mobileSearchLoading) return;
        e.preventDefault();

        let page = parseInt(btn.getAttribute("data-page")) + 1;
        mobileSearchLoading = true;
        btn.disabled = true;
        btn.textContent = "جاري التحميل...";

        try {
            const params = new URLSearchParams(window.location.search);
            params.set('page', String(page));
            params.set('view', 'mobile');
            const resp = await fetch(`{{ route('search') }}?${params.toString()}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            if (!resp.ok) throw new Error("خطأ في السيرفر");
            const trimmed = (await resp.text()).trim();
            if (!trimmed) {
                btn.closest("#mobile-search-load-more-container")?.remove();
            } else {
                const list = document.getElementById("mobile-search-container");
                if (list) list.insertAdjacentHTML("beforeend", trimmed);
                btn.setAttribute("data-page", page);
                btn.disabled = false;
                btn.textContent = "المزيد";
            }
        } catch (err) {
            alert("خطأ في تحميل المزيد");
            btn.disabled = false;
            btn.textContent = "المزيد";
        }
        mobileSearchLoading = false;
    });
</script>


