@extends('layouts.index')

@section('title', 'أصوات جزائرية | صور')

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

    <div class="web">
        @include('user.components.fixed-nav')

        {{-- Container --}}
        <div class="container">

            {{-- Title --}}
            <div class="title">
                <p class="section-title">صور</p>
                @include('user.components.ligne')
                <div class="under-title-ligne-space"></div>
            </div>

            {{-- Feature Photos --}}
            <style>
                .section-title {
                    font-size: 32px;
                }

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
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    border-radius: 4px;
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
                }

                .custom-photos-feature .custom-content h3 {
                    margin: 0;
                    color: #999;
                    font-size: 12px;
                    font-family: asswat-light;
                    font-weight: lighter;
                    cursor: pointer;
                }

                .custom-photos-feature .custom-content h2 {
                    margin: 10px 0 10px;
                    font-size: 24px;
                    line-height: 1.3;
                    font-family: asswat-bold;
                    cursor: pointer;
                    transition: .2s;
                }

                .custom-photos-feature .custom-content p {
                    margin: 0;
                    font-size: 17px;
                    line-height: 1.6;
                    color: #555;
                }

                .custom-photos-feature .custom-content h2:hover {
                    text-decoration: underline;
                }
            </style>

            @if ($featured)
                <div class="custom-photos-feature">
                    <a href="{{ route('news.show', $featured->shortlink) }}">
                        <div class="custom-image-wrapper">
                            <img loading="lazy" decoding="async" src="{{ $featured->media()->wherePivot('type', 'main')->first()->path }}"
                                alt="{{ $featured->title }}"
                                style="aspect-ratio: 16 / 9; object-fit: cover; width: 100%; display: block;">
                            <div class="custom-corner-icon">
                                <i class="fas fa-images"></i>
                            </div>
                        </div>
                    </a>
                    <div class="custom-content">
                        <h3><x-category-links :content="$featured" /></h3>
                        <a href="{{ route('news.show', $featured->shortlink) }}"
                            style="text-decoration: none; color: inherit;">
                            <h2>{{ $featured->title }}</h2>
                        </a>
                        <p class="article-desc">{{ $featured->summary }}</p>
                    </div>
                </div>
            @endif

            {{-- Photos Grid --}}
            <style>
                .photos-section-wrapper {
                    display: grid;
                    grid-template-columns: 10fr 2fr;
                    /* 8/12 محتوى + 4/12 فارغ */
                    gap: 20px;
                }

                .photos-section-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                }

                .photos-section-item {
                    position: relative;
                }

                .photos-section-item-image-wrapper {
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                .photos-section-item img {
                    width: 100%;
                    aspect-ratio: 16 / 9;
                    object-fit: cover;
                    display: block;
                }

                .photos-section-item-play-icon {
                    position: absolute;
                    bottom: 10px;
                    left: 10px;
                    width: 40px;
                    height: 40px;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    border-radius: 4px;
                    z-index: 2;
                }

                .photos-section-item h2 {
                    font-size: 18px;
                    margin: 0 0 8px 0;
                    font-family: asswat-bold;
                    line-height: 1.4;
                    cursor: pointer;
                    transition: .2s;
                }

                .photos-section-item h3 {
                    font-size: 12px;
                    margin: 8px 0 4px;
                    color: #74747C;
                    font-family: asswat-light;
                    font-weight: lighter;
                    cursor: pointer;
                }

                .photos-section-item h2:hover {
                    text-decoration: underline;
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
            </style>

            {{-- Photos Grid --}}
            <div class="photos-section-wrapper">
                <div>
                    <div class="photos-section-grid" id="photos-container">
                        @include('user.partials.photo-items', ['otherPhotos' => $otherPhotos])
                    </div>

                    @if (count($otherPhotos) >= 9)
                        <div class="text-center mt-3" id="load-more-container">
                            <button class="photos-load-more-btn" data-page="1">المزيد</button>
                        </div>
                    @else
                        <div style="height: 60px;"></div>
                    @endif
                </div>
                <div class="photos-section-empty"></div>
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

        <!-- Mobile Photos Content -->
        <div class="mobile-flow">
            <div class="mobile-container" style="margin-top: 68px;">
                <div class="mobile-simple-list" dir="rtl">
                    <h2 class="mobile-simple-header">صور</h2>
                    <div style="padding: 0px 16px">
                        @include('user.components.ligne')
                    </div>
                    <ul class="mobile-simple-ul" role="list" id="mobile-photos-container">
                        @forelse ($otherPhotos as $item)
                            @include('user.mobile.item')
                        @empty
                            <li style="padding: 12px 0; text-align: center; color: #999;">لا توجد صور حالياً.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Mobile Load More button -->
            @if (count($otherPhotos) >= 9)
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
    </style>

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

    let loading = false;

    document.addEventListener("click", async function(e) {
        // Desktop load more
        if (e.target.classList.contains("photos-load-more-btn")) {
            if (loading) return;

            let btn = e.target;
            let page = parseInt(btn.getAttribute("data-page")) + 1;

            loading = true;
            btn.disabled = true;
            btn.textContent = "جاري التحميل...";

            try {
                // Desktop should use the default photo partials (no view=mobile)
                let response = await fetch(`{{ route('photos') }}?page=${page}`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("خطأ في السيرفر");

                let data = await response.text();

                if (data.trim().length === 0) {
                    btn.closest("#load-more-container").remove();
                } else {
                    document.querySelector("#photos-container").insertAdjacentHTML("beforeend", data);
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
                // Mobile explicitly asks for mobile view so backend returns mobile-simple-item
                let response = await fetch(`{{ route('photos') }}?page=${page}&view=mobile`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("خطأ في السيرفر");

                let data = await response.text();
                const trimmed = data.trim();

                if (trimmed.length === 0) {
                    const cont = btn.closest("#mobile-load-more-container");
                    if (cont) cont.remove();
                } else {
                    const mobileContainer = document.getElementById("mobile-photos-container");
                    if (mobileContainer) {
                        // Backend already returns fully rendered <li class="mobile-simple-item"> items
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

