@extends('layouts.index')

@php
    $shareTitle = $theme->name ?? ($theme->title ?? 'أصوات جزائرية');
    $shareImage = $theme->social_image ?: $theme->image;
    $shareDescription = $theme->description;
@endphp

@section('title', !empty($shareDescription) ? $shareDescription : $shareTitle)

@section('meta_og_title', $shareTitle)
@section('meta_twitter_title', $shareTitle)

@if (!empty($shareImage))
    @section('meta_og_image', asset($shareImage))
    @section('meta_twitter_image', asset($shareImage))
@endif

@if (!empty($shareDescription))
    @section('meta_description', $shareDescription)
    @section('meta_og_description', $shareDescription)
    @section('meta_twitter_description', $shareDescription)
@endif

@push('seo')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close,dehaze" />
@endpush

@section('content')

    <style>
        html,
        body {
            overflow-x: hidden;
        }

        /* Reserve the scrollbar gutter so locking scroll doesn't shift the layout */
        html {
            scrollbar-gutter: stable;
        }

        html.tm-no-scroll,
        html.tm-no-scroll body {
            overflow: hidden;
        }

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
            background: #f5f5f5;
        }

        .newCategory-all-card-image img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            display: block;
        }

        .newCategory-all-card-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 18px 20px;
            /* Match the image height (image is full-width with 4/3 ratio) */
            aspect-ratio: 4 / 3;
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

        .newCategory-all-card-date {
            margin-top: auto;
            padding-top: 12px;
            font-family: asswat-regular;
            font-size: 14px;
            color: #888;
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

        .theme-hero-full {
            position: relative;
            width: 100vw;
            margin-left: calc(-50vw + 50%);
            min-height: 520px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            direction: rtl;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .theme-hero-header {
            width: 100%;
            padding: 30px 45px;
            position: relative;
            /* Above the slide-out panel (2001) so the menu/close button stays on top.
               .theme-hero-full is display:flex, so this header is a flex item and its
               z-index establishes a stacking context that would otherwise trap the button. */
            z-index: 3000;
        }

        .theme-hero-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            max-width: 100%;
            margin: 0 auto;
        }

        .theme-hero-right-group {
            display: flex;
            align-items: center;
            gap: 80px;
        }

        .theme-hero-logo img {
            height: 40px;
            width: auto;
            display: block;
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        .theme-hero-menu-btn .material-symbols-outlined {
            font-size: 34px;
            line-height: 1;
            transition: transform .3s ease;
        }

        .theme-hero-menu-btn.is-open .material-symbols-outlined {
            transform: rotate(180deg);
        }

        /* Hamburger menu button */
        .theme-hero-menu-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 50px;
            line-height: 1;
            cursor: pointer;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            transition: opacity .2s ease;
            position: relative;
            z-index: 3000;
        }

        .theme-hero-menu-btn:hover {
            opacity: .75;
        }

        /* Hero header search (icon toggles an expandable input, like the fixed nav) */
        .theme-hero-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .theme-hero-search {
            display: flex;
            align-items: center;
        }

        .theme-hero-search.hide-search {
            display: none;
        }

        .theme-hero-search-input {
            display: none;
            width: 240px;
            max-width: 40vw;
            border: none;
            background: rgba(255, 255, 255, 0.12);
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
            color: #fff;
            padding: 11px 16px;
            outline: none;
            font-family: asswat-regular;
            border-radius: 0;
            margin-left: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        }

        .theme-hero-search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .theme-hero-search-input.active {
            display: inline-block;
        }

        .theme-hero-search-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            padding: 6px 8px;
            transition: opacity .2s ease;
        }

        .theme-hero-search-btn:hover {
            opacity: .75;
        }

        /* Slide-out menu panel */
        .theme-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity .9s ease, visibility .9s;
            z-index: 2000;
        }

        .theme-menu-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .theme-menu-panel {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 40vw;
            max-width: 90vw;
            background: rgba(37, 37, 37, 0);
            padding: 90px 28px 30px;
            transform: translateX(-100%);
            transition: transform .9s cubic-bezier(0.22, 1, 0.36, 1), background-color .9s ease;
            z-index: 2001;
            overflow-y: auto;
            /* Hide scrollbar while keeping scroll */
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .theme-menu-panel::-webkit-scrollbar {
            display: none;
        }

        .theme-menu-panel.open {
            transform: translateX(0);
            background: rgba(37, 37, 37, 1);
        }

        /* Not used: the hamburger button itself is the close (toggles to X) */
        .theme-menu-close {
            display: none;
        }

        .theme-menu-close:hover {
            opacity: .7;
        }

        .theme-menu-search {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 4px 10px;
            margin-bottom: 26px;
        }

        .theme-menu-search-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: #fff;
            font-family: asswat-regular;
            font-size: 16px;
            padding: 8px 4px;
        }

        .theme-menu-search-input::placeholder {
            color: rgba(255, 255, 255, 0.55);
        }

        .theme-menu-search-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 17px;
            cursor: pointer;
            padding: 4px 6px;
        }

        /* ===== Sidebar menu list (mobile-sidebar style) ===== */
        .theme-menu-panel nav ul.tm-menu-list,
        .tm-menu-list {
            list-style: none;
            margin: 0;
            padding: 12px 16px 0;
            display: block !important;
        }

        .tm-menu-list > li {
            display: block;
            border-bottom: none;
        }

        /* Inset divider under each row: symmetric empty space left & right */
        .tm-menu-list > li > a,
        .tm-menu-list > li > .tm-item-header {
            margin: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .tm-menu-list > li:last-child > a,
        .tm-menu-list > li:last-child > .tm-item-header {
            border-bottom: none;
        }

        /* Titles align flush with the divider start (no extra horizontal inset) */
        .tm-menu-list > li > a {
            padding: 16px 0;
        }

        .tm-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0;
            padding: 0;
        }

        .tm-item-header > a {
            flex: 1;
            padding: 16px 0;
        }

        /* Shared row typography */
        .tm-menu-list > li > a,
        .tm-item-header > a {
            display: block;
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            font-family: 'asswat-bold';
            font-weight: 500;
            transition: all .2s ease;
        }

        .tm-menu-list > li > a:hover,
        .tm-item-header > a:hover {
            opacity: .85;
        }

        /* Submenu accordion toggle (chevron) */
        .tm-submenu-toggle {
            background: none;
            border: none;
            padding: 0;
            margin-left: 4px;
            cursor: pointer;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            transition: all .3s ease;
        }

        .tm-toggle-arrow {
            display: inline-block;
            transition: transform .3s ease;
            font-size: 18px;
            line-height: 1;
        }

        .tm-submenu-toggle.active .tm-toggle-arrow {
            transform: rotate(90deg);
        }

        /* News sections: collapsible accordion */
        .theme-menu-panel nav ul.tm-submenu,
        .tm-submenu {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height .35s ease;
            display: block !important;
        }

        .theme-menu-panel nav ul.tm-submenu.active,
        .tm-submenu.active {
            max-height: 800px;
        }

        /* Override any global `li { display: flex }` so the row fills the panel */
        .tm-submenu > li {
            display: block;
            width: 100%;
        }

        .tm-submenu li > a {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .tm-submenu li:last-child > a {
            border-bottom: none;
        }

        .tm-submenu a {
            display: block;
            padding: 12px 24px;
            color: #828282;
            text-decoration: none;
            font-size: 16px;
            font-family: 'asswat-bold';
            font-weight: 400;
            transition: all .2s ease;
        }

        .tm-submenu a:hover {
            color: #fff;
        }

        /* Social row at the bottom */
        .tm-social {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            row-gap: 16px;
            padding: 8px 16px 20px;
            margin-top: 26px;
        }

        .tm-social .s-icon {
            width: 22px;
            height: 22px;
            color: #fff;
            opacity: .85;
            display: inline-flex;
            transition: opacity .2s ease;
        }

        .tm-social .s-icon:hover {
            opacity: 1;
        }

        .tm-social .s-icon svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        /* ===== Sidebar polish ===== */
        .theme-menu-overlay {
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .theme-menu-search {
            transition: border-color .2s ease, background .2s ease;
        }

        .theme-menu-search:focus-within {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.12);
        }

        /* Staggered entrance for menu items when panel opens */
        .theme-menu-panel .theme-menu-search,
        .theme-menu-panel .tm-menu-list > li {
            opacity: 0;
            transform: translateX(-14px);
            transition: opacity .3s ease, transform .3s ease;
        }

        .theme-menu-panel.open .theme-menu-search,
        .theme-menu-panel.open .tm-menu-list > li {
            opacity: 1;
            transform: translateX(0);
        }

        .theme-menu-panel.open .theme-menu-search { transition-delay: .1s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(1) { transition-delay: .14s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(2) { transition-delay: .18s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(3) { transition-delay: .22s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(4) { transition-delay: .26s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(5) { transition-delay: .3s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(6) { transition-delay: .34s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(7) { transition-delay: .38s; }
        .theme-menu-panel.open .tm-menu-list > li:nth-child(8) { transition-delay: .42s; }

        .theme-hero-nav {
            list-style: none;
            display: flex;
            gap: 28px;
            margin: 0;
            padding: 0;
        }

        .theme-hero-nav a {
            color: #fff;
            text-decoration: none;
            font-family: asswat-medium;
            font-size: 16px;
        }

        .theme-hero-nav a:hover {
            font-weight: 700;
        }

        .theme-hero-nav-item.has-sub {
            position: relative;
        }

        .theme-hero-nav-item.has-sub > a i {
            font-size: 11px;
            margin-right: 4px;
        }

        .theme-hero-nav-item.has-sub > a {
            padding-bottom: 32px;
            margin-bottom: -32px;
        }

        .theme-hero-subnav {
            position: absolute;
            top: calc(100% + 14px);
            right: 50%;
            transform: translateX(50%) translateY(-8px);
            list-style: none;
            margin: 0;
            padding: 14px 18px;
            background: #fff;
            min-width: 360px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2px 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .18s ease, transform .18s ease, visibility .18s;
            z-index: 20;
        }

        .theme-hero-nav-item.has-sub:hover .theme-hero-subnav,
        .theme-hero-nav-item.has-sub:focus-within .theme-hero-subnav {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(50%) translateY(0);
        }

        .theme-hero-subnav li {
            margin: 0;
        }

        .theme-hero-subnav li a {
            display: block;
            padding: 9px 12px;
            color: #1a1a1a;
            font-family: asswat-medium;
            font-size: 15px;
            text-decoration: none;
            white-space: nowrap;
            border-bottom: 1px solid #f0f0f0;
            transition: color .15s ease, padding-right .15s ease;
        }

        .theme-hero-subnav li:nth-last-child(-n+2) a {
            border-bottom: none;
        }

        .theme-hero-subnav li a:hover {
            color: #000;
            padding-right: 18px;
            font-weight: 700;
        }


        .theme-hero-title-wrap {
            padding: 40px 10px;
            max-width: 1320px;
            width: 100%;
            margin: 0 auto;
        }

        .theme-hero-title {
            font-family: asswat-bold;
            font-size: 48px;
            color: #fff;
            margin: 0;
            line-height: 1.2;
            text-align: right;
        }

        .theme-hero-mobile {
            position: relative;
            width: 100%;
            min-height: 210px;
            display: flex;
            align-items: flex-end;
            direction: rtl;
            margin-bottom: 12px;
        }

        .theme-hero-title-mobile {
            font-family: asswat-bold;
            font-size: 26px;
            color: #fff;
            margin: 0;
            padding: 16px;
            line-height: 1.25;
            text-align: right;
            width: 100%;
        }

        #mobile-tags-container .mobile-simple-item {
            background: #f5f5f5 !important;
            margin-bottom: 16px;
        }

        #mobile-tags-container .mobile-more-link {
            padding: 0 !important;
        }

        #mobile-tags-container .mobile-more-link .ms-thumb img {
            aspect-ratio: 4/3 !important;
            border-radius: 0 !important;
        }

        #mobile-tags-container .mobile-more-link .ms-text {
            padding: 14px 16px !important;
        }
    </style>

    <div class="web">
        @if (!(in_array($type, ['Window', 'Trend']) && !empty($theme->image)))
            @include('user.components.fixed-nav')
        @endif

        @if (in_array($type, ['Window', 'Trend']) && !empty($theme->image))
            @include('user.components.admin-top-bar')
            <div class="theme-hero-full"
                style="background: linear-gradient(rgba(0,0,0,0.15), rgba(0,0,0,0.75)), url('{{ asset($theme->image) }}') center top/cover no-repeat;">
                <header class="theme-hero-header">
                    <div class="theme-hero-header-inner">
                        <a href="{{ route('index') }}" class="theme-hero-logo">
                            <img src="{{ asset('user/assets/images/white_logo.svg') }}" alt="Logo">
                        </a>
                        <div class="theme-hero-actions">
                            <form action="{{ route('search') }}" method="GET" class="theme-hero-search">
                                <input name="query" type="text" class="theme-hero-search-input" placeholder="ابحث...">
                                <button type="submit" class="theme-hero-search-btn" aria-label="بحث">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                            <button type="button" class="theme-hero-menu-btn" id="themeMenuBtn"
                                aria-label="القائمة" aria-expanded="false" aria-controls="themeMenuPanel">
                                <span class="material-symbols-outlined">dehaze</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Slide-out menu (links + search) -->
                <div class="theme-menu-overlay" id="themeMenuOverlay"></div>
                <aside class="theme-menu-panel" id="themeMenuPanel" aria-hidden="true" dir="rtl">
                    <button type="button" class="theme-menu-close" id="themeMenuClose" aria-label="إغلاق">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                    <nav>
                        <ul class="tm-menu-list">
                            <li class="tm-item-with-submenu">
                                <div class="tm-item-header">
                                    <a href="{{ route('latestNews') }}">أخبار</a>
                                    <button type="button" class="tm-submenu-toggle" aria-label="عرض الأقسام">
                                        <i class="fa-solid fa-chevron-left tm-toggle-arrow" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <ul class="tm-submenu">
                                    <li><a href="{{ route('newSection', ['section' => 'algeria']) }}">الجزائر</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'world']) }}">عالم</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'economy']) }}">اقتصاد</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'sports']) }}">رياضة</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'people']) }}">ناس</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'culture']) }}">ثقافة وفنون</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'technology']) }}">تكنولوجيا</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'health']) }}">صحة</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'environment']) }}">بيئة</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'media']) }}">ميديا</a></li>
                                    <li><a href="{{ route('newSection', ['section' => 'variety']) }}">منوعات</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('reviews') }}">آراء</a></li>
                            <li><a href="{{ route('windows') }}">نوافذ</a></li>
                            <li><a href="{{ route('files') }}">ملفات</a></li>
                            <li><a href="{{ route('investigation') }}">فحص</a></li>
                            <li><a href="{{ route('videos') }}">فيديو</a></li>
                            <li><a href="{{ route('podcasts') }}">بودكاست</a></li>
                            <li><a href="{{ route('photos') }}">صور</a></li>
                        </ul>

                        <div class="tm-social" aria-label="تابعنا">
                            <a href="https://www.facebook.com/asswatdjazairia" target="_blank" class="s-icon" aria-label="Facebook">@include('user.icons.facebook')</a>
                            <a href="https://x.com/asswatdjazairia" target="_blank" class="s-icon" aria-label="Twitter">@include('user.icons.twitter')</a>
                            <a href="https://www.youtube.com/@asswatdjazairia" target="_blank" class="s-icon" aria-label="YouTube">@include('user.icons.youtube')</a>
                            <a href="https://www.instagram.com/asswatdjazairia" target="_blank" class="s-icon" aria-label="Instagram">@include('user.icons.instagram')</a>
                            <a href="https://t.me/AsswatDjazairia" target="_blank" class="s-icon" aria-label="Telegram">@include('user.icons.telegram')</a>
                            <a href="https://www.linkedin.com/in/asswatdjazairia/" target="_blank" class="s-icon" aria-label="LinkedIn">@include('user.icons.linkedin')</a>
                        </div>
                    </nav>
                </aside>
                <div class="theme-hero-title-wrap">
                    <h1 class="theme-hero-title">{{ $theme->name ?? ($theme->title ?? 'الأخبار') }}</h1>
                </div>
            </div>
        @endif

        <div class="container">
            @if (!(in_array($type, ['Window', 'Trend']) && !empty($theme->image)))
                <div class="title">
                    <p class="section-title">{{ $theme->title ?? 'الأخبار' }}</p>
                    @include('user.components.ligne')
                    <div class="under-title-ligne-space"></div>
                </div>
            @else
                <div class="under-title-ligne-space"></div>
            @endif

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
                                <p>{{ $item->summary }}</p>
                                @php
                                    $arMonths = ['01' => 'جانفي','02' => 'فيفري','03' => 'مارس','04' => 'أفريل','05' => 'ماي','06' => 'جوان','07' => 'جويلية','08' => 'أوت','09' => 'سبتمبر','10' => 'أكتوبر','11' => 'نوفمبر','12' => 'ديسمبر'];
                                    $cardDate = $item->published_date ?? $item->published_at ?? $item->created_at;
                                    $cardDate = is_string($cardDate) ? \Carbon\Carbon::parse($cardDate) : $cardDate;
                                @endphp
                                @if ($cardDate)
                                    <span class="newCategory-all-card-date">{{ $cardDate->format('d') }} {{ $arMonths[$cardDate->format('m')] }} {{ $cardDate->format('Y') }}</span>
                                @endif
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

        <!-- Mobile Trends Content -->
        <div class="mobile-flow">
            <div class="mobile-container" style="margin-top: 68px;">
                <div class="mobile-simple-list" dir="rtl">
                    @if (in_array($type, ['Window', 'Trend']) && !empty($theme->image))
                        <div class="theme-hero-mobile"
                            style="background: linear-gradient(rgba(0,0,0,0.0), rgba(0,0,0,0.75)), url('{{ asset($theme->image) }}') center top/cover no-repeat;">
                            <h2 class="theme-hero-title-mobile">{{ $theme->name ?? ($theme->title ?? 'الأخبار') }}</h2>
                        </div>
                    @else
                        <h2 class="mobile-simple-header">{{ $theme->title ?? 'الأخبار' }}</h2>
                    @endif
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
        // Theme hero slide-out menu toggle
        (function() {
            const btn = document.getElementById('themeMenuBtn');
            const panel = document.getElementById('themeMenuPanel');
            const overlay = document.getElementById('themeMenuOverlay');
            const closeBtn = document.getElementById('themeMenuClose');
            if (!btn || !panel || !overlay) return;
            const btnIcon = btn.querySelector('.material-symbols-outlined');

            const heroSearches = document.querySelectorAll('.theme-hero-search');

            function openMenu() {
                panel.classList.add('open');
                overlay.classList.add('open');
                btn.classList.add('is-open');
                document.documentElement.classList.add('tm-no-scroll');
                if (btnIcon) btnIcon.textContent = 'close';
                btn.setAttribute('aria-expanded', 'true');
                panel.setAttribute('aria-hidden', 'false');
                heroSearches.forEach(function(s) {
                    s.classList.add('hide-search');
                    const i = s.querySelector('.theme-hero-search-input');
                    if (i) i.classList.remove('active');
                });
            }

            function closeMenu() {
                panel.classList.remove('open');
                overlay.classList.remove('open');
                btn.classList.remove('is-open');
                document.documentElement.classList.remove('tm-no-scroll');
                if (btnIcon) btnIcon.textContent = 'dehaze';
                btn.setAttribute('aria-expanded', 'false');
                panel.setAttribute('aria-hidden', 'true');
                heroSearches.forEach(function(s) {
                    s.classList.remove('hide-search');
                });
            }

            btn.addEventListener('click', function() {
                if (panel.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
            overlay.addEventListener('click', closeMenu);
            if (closeBtn) closeBtn.addEventListener('click', closeMenu);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });
        })();

        // Hero header search toggle (mirrors the fixed-nav search behaviour)
        (function() {
            const forms = document.querySelectorAll('.theme-hero-search');
            if (!forms.length) return;

            forms.forEach(function(form) {
                const input = form.querySelector('.theme-hero-search-input');
                const btn = form.querySelector('.theme-hero-search-btn');
                if (!input || !btn) return;

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (input.classList.contains('active')) {
                        if (input.value.trim().length > 0) return; // let the form submit
                        e.preventDefault();
                        input.classList.remove('active');
                        input.blur();
                    } else {
                        e.preventDefault();
                        input.classList.add('active');
                        input.focus();
                    }
                });
            });

            document.addEventListener('click', function(e) {
                forms.forEach(function(form) {
                    if (!form.contains(e.target)) {
                        const input = form.querySelector('.theme-hero-search-input');
                        if (input) input.classList.remove('active');
                    }
                });
            });
        })();

        // Sidebar submenu accordion (news sections)
        (function() {
            document.querySelectorAll('.tm-submenu-toggle').forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const item = toggle.closest('.tm-item-with-submenu');
                    const submenu = item ? item.querySelector('.tm-submenu') : null;
                    toggle.classList.toggle('active');
                    if (submenu) submenu.classList.toggle('active');
                });
            });
        })();

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
                    let response = await fetch(`/trend/${categoryId}?page=${page}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    if (!response.ok) throw new Error("خطأ في السيرفر");

                    let data = await response.text();

                    const trimmed = data.trim();
                    if (trimmed.length === 0) {
                        btn.closest("#load-more-container").remove();
                    } else {
                        document.getElementById("category-container").insertAdjacentHTML("beforeend", trimmed);
                        const itemCount = (trimmed.match(/newCategory-all-card/g) || []).length;
                        if (itemCount < 9) {
                            btn.closest("#load-more-container").remove();
                        } else {
                            btn.setAttribute("data-page", page);
                            btn.disabled = false;
                            btn.textContent = "المزيد";
                        }
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
                    let response = await fetch(`/trend/${categoryId}?page=${page}&view=mobile`, {
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
                        const itemCount = (trimmed.match(/mobile-simple-item/g) || []).length;
                        if (itemCount < 9) {
                            const cont = btn.closest("#mobile-load-more-container");
                            if (cont) cont.remove();
                        } else {
                            btn.setAttribute("data-page", page);
                            btn.disabled = false;
                            btn.textContent = "المزيد";
                        }
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
