@extends('layouts.index')

@section('title', 'أصوات جزائرية | آراء')

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

    <style>
        .custom-photos-feature {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 60px;
        }

        .custom-photos-feature .custom-image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .custom-photos-feature img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .custom-photos-feature .custom-corner-icon {
            position: absolute;
            bottom: 15px;
            left: 20px;
            width: 45px;
            height: 45px;
            color: white;
        }

        .custom-photos-feature .custom-corner-icon img {
            width: 100%;
            height: 100%;
        }

        .custom-photos-feature .custom-content {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 20px;
            gap: 8px;
        }

        .custom-photos-feature .custom-content > * { margin: 0 !important; }

        .custom-photos-feature .custom-content .feat-writer {
            font-size: 16px;
            font-family: asswat-medium;
            color: #555;
        }

        .custom-photos-feature .custom-content .feat-writer a {
            color: inherit;
            text-decoration: none;
        }

        .custom-photos-feature .custom-content .feat-writer a:hover {
            text-decoration: underline;
        }

        .custom-photos-feature .custom-content h2 {
            font-size: 26px;
            line-height: 1.3;
            font-family: asswat-bold;
            cursor: pointer;
            transition: .2s;
        }

        .custom-photos-feature .custom-content p {
            font-size: 17px;
            line-height: 1.6;
            color: #555;
        }

        .custom-photos-feature .custom-content .feat-date {
            font-size: 14px;
            color: #999;
            font-family: asswat-regular;
        }

        .custom-photos-feature .custom-content h2:hover {
            text-decoration: underline;
        }

        /* Grid Section */
        .custom-grid {
            display: grid;
            grid-template-columns: 9fr 3fr;
            gap: 40px;
            margin-bottom: 60px;
            align-items: flex-start;
        }

        .custom-cards-wrapper {
            display: flex;
            flex-direction: column;
            margin-bottom: 60px;
        }

        .custom-card {
            display: flex;
            flex-direction: row;
            gap: 20px;
            padding: 20px 0;
            align-items: align-start;
        }

        .custom-card:last-child {
            border-bottom: none;
        }

        .custom-card .custom-image {
            width: 90px;
            height: 90px;
            flex-shrink: 0;
        }

        .custom-card .custom-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        .custom-card .custom-texts {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .custom-card .custom-texts h2 {
            margin: 0 0 5px;
            font-size: 24px;
            line-height: 1.3;
            font-family: asswat-bold;
            cursor: pointer;
            transition: .2s;
        }

        .custom-card .custom-texts p {
            margin: 20px 0 0 0;
            font-size: 17px;
            line-height: 1.5;
            color: #555;
        }

        .custom-card .custom-texts span {
            font-size: 17px;
            color: #999;
            font-family: asswat-regular;
            cursor: pointer;
        }

        .custom-card .custom-texts h2:hover {
            text-decoration: underline;
        }

        .reviews-load-more-btn {
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

        .section-title {
            font-size: 32px;
        }

        .review-writer {
            margin: 0 0 8px;
            font-size: 16px;
            font-family: asswat-medium;
            color: #555;
        }

        .review-writer a {
            color: inherit;
            text-decoration: none;
        }

        .review-writer a:hover {
            text-decoration: underline;
        }

        .alert-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: #6c757d;
            margin: 20px 0;
        }
    </style>

    <div class="web">
        @include('user.components.fixed-nav')

        {{-- Container --}}
        <div class="container">

            {{-- Title --}}
            <div class="title">
                <p class="section-title">آراء</p>
                @include('user.components.ligne')
                <div class="under-title-ligne-space"></div>
            </div>

            {{-- Feature Section --}}
            @if (isset($reviews) && count($reviews) > 0 && isset($reviews[0]))
                <div class="custom-photos-feature">
                    <a href="{{ route('news.show', $reviews[0]->shortlink ?? '') }}">
                        <div class="custom-image-wrapper">
                            <img loading="lazy" decoding="async" src="{{ $reviews[0]->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/placeholder.jpg' }}"
                                alt="{{ $reviews[0]->title ?? 'رأي مميز' }}">
                        </div>
                    </a>
                    <div class="custom-content">
                        @php
                            $featuredWriter = $reviews[0]->writers()->first();
                            $arabicMonths = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
                        @endphp
                        @if ($featuredWriter)
                            <div class="feat-writer">
                                <a href="{{ route('writer.show', $featuredWriter->id) }}">{{ $featuredWriter->name }}</a>
                            </div>
                        @endif
                        <a href="{{ route('news.show', $reviews[0]->shortlink ?? '') }}"
                            style="text-decoration: none; color: inherit;">
                            <h2>{{ $reviews[0]->title ?? 'عنوان الرأي' }}</h2>
                        </a>
                        <p class="article-desc">{{ $reviews[0]->summary ?? 'ملخص الرأي' }}</p>
                        <div class="feat-date">
                            {{ $reviews[0]->created_at->locale('ar')->translatedFormat('d') }}
                            {{ $arabicMonths[$reviews[0]->created_at->month - 1] }}
                            {{ $reviews[0]->created_at->locale('ar')->translatedFormat('Y') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <p>لا توجد آراء متاحة حالياً.</p>
                </div>
            @endif

            <div style="display: flex; width: 100%; gap: 40px;">
                <div style="flex: 7;">
                    {{-- Grid Section --}}
                    @if (isset($otherReviews) && count($otherReviews) > 0)
                        <div class="custom-cards-wrapper" id="reviews-container">
                            {{-- Cards 1-10 --}}
                            @include('user.partials.review-items', ['otherReviews' => $otherReviews])
                        </div>
                        {{-- Pagination Button --}}
                        @if (count($otherReviews) >= 5)
                            <div class="text-center mt-3" id="load-more-container">
                                <button class="reviews-load-more-btn btn btn-primary" data-page="1">
                                    المزيد
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <p>لا توجد المزيد من الآراء.</p>
                        </div>
                    @endif
                </div>
                <div style="flex: 3;"></div>
            </div>
        </div>

        @include('user.components.footer')

    </div>




    <div class="mobile">
        @include('user.mobile.mobile-home')

        <!-- Grey navigation bar -->
        <div id="greybar"
            style="background-color: #252525; height: 68px; position: fixed; top: 0; left: 0; right: 0; z-index: 10;">
        </div>

        <!-- Mobile Reviews Content -->
        <div class="mobile-flow">
            <div class="mobile-container" style="margin-top: 68px;">
                <div class="mobile-simple-list" dir="rtl">
                    <h2 class="mobile-simple-header">آراء</h2>
                    <div style="padding: 0px 16px">
                        @include('user.components.ligne')
                    </div>
                    <ul class="mobile-simple-ul" role="list" id="mobile-reviews-container">
                        @if (isset($reviews) && count($reviews) > 0 && isset($reviews[0]))
                            @include('user.mobile.writer-item', ['item' => $reviews[0]])
                        @endif
                        @forelse ($otherReviews as $item)
                            @include('user.mobile.writer-item')
                        @empty
                            <li style="padding: 12px 0; text-align: center; color: #999;">لا توجد آراء حالياً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Mobile Load More button -->
            @if (count($otherReviews) >= 5)
                <div class="text-center" id="mobile-load-more-container">
                    <button class="mobile-load-more-btn" data-page="1">المزيد</button>
                </div>
            @endif
            <div style="height: 40px;"></div>

            <!-- Mobile Footer -->
            @include('user.mobile.footer')

        </div>

    </div>

    <style>
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
            margin: 4px 0px;
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
    </style>

@endsection

{{-- Scripts --}}
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

    document.addEventListener("click", async function(e) {
        // Desktop load more
        if (e.target.classList.contains("reviews-load-more-btn")) {
            if (loading) return;

            let btn = e.target;
            let page = parseInt(btn.getAttribute("data-page")) + 1;

            // Check if we have a valid page number
            if (isNaN(page)) {
                console.error("Invalid page number");
                return;
            }

            loading = true;
            btn.disabled = true;
            btn.textContent = "جاري التحميل...";

            try {
                let response = await fetch(`{{ route('reviews') }}?page=${page}`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("خطأ في السيرفر");

                let data = await response.text();

                if (data.trim().length === 0) {
                    // Safely remove load more container
                    let container = btn.closest("#load-more-container");
                    if (container) {
                        container.remove();
                    }
                } else {
                    let reviewsContainer = document.querySelector("#reviews-container");
                    if (reviewsContainer) {
                        reviewsContainer.insertAdjacentHTML("beforeend", data);
                        btn.setAttribute("data-page", page);
                        btn.disabled = false;
                        btn.textContent = "المزيد";
                    }
                }
            } catch (error) {
                console.error("Error loading more reviews:", error);
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
                let response = await fetch(`{{ route('reviews') }}?page=${page}&view=mobile`, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                if (!response.ok) throw new Error("خطأ في السيرفر");

                let data = await response.text();
                const trimmed = data.trim();

                if (trimmed.length === 0) {
                    const cont = btn.closest("#mobile-load-more-container");
                    if (cont) cont.remove();
                } else {
                    const mobileContainer = document.getElementById("mobile-reviews-container");
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

