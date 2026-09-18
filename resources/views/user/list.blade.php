<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="{{ ($news->status ?? 'published') === 'published' ? 'index, follow' : 'noindex, nofollow' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Use project fonts instead of Google Poppins -->
    <link rel="stylesheet" href="{{ asset('user/css/fonts.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=close,dehaze,expand_content" />
     <!-- ================= FAVICON ================= -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('user/assets/images/icon-logo.svg') }}" />

    <script>
        // Function to process social embeds (Instagram, Facebook, X/Twitter)
        function processEmbeds() {
            if (window.instgrm && window.instgrm.Embed) {
                try { window.instgrm.Embed.process(); } catch (e) { console.error('Instagram error:', e); }
            }
            if (window.FB && window.FB.XFBML && typeof window.FB.XFBML.parse === 'function') {
                try { window.FB.XFBML.parse(); } catch (e) { console.error('Facebook error:', e); }
            }
            if (window.twttr && window.twttr.widgets) {
                try { window.twttr.widgets.load(); } catch (e) { console.error('Twitter/X error:', e); }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.intro a').forEach(function(el) {
                el.setAttribute('target', '_blank');
                el.setAttribute('rel', 'noopener');
            });

            // Process embeds at different intervals as scripts load
            processEmbeds();
            setTimeout(processEmbeds, 500);
            setTimeout(processEmbeds, 1500);
            setTimeout(processEmbeds, 3000);
            setTimeout(processEmbeds, 5000);
        });
    </script>

    <style>
        /* ==================== BASE STYLES ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html {
            overflow-x: hidden;
            /* Reserve the scrollbar gutter so locking scroll doesn't shift the layout */
            scrollbar-gutter: stable;
        }

        html.tm-no-scroll,
        html.tm-no-scroll body {
            overflow: hidden;
        }

        ul, ol {
            list-style: none;
        }

        a {
            color: inherit;
            text-decoration: none;
        }


        body {
            font-family: 'asswat-regular', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #fff;
            overflow-x: hidden;
        }

        body.has-admin-bar {
            padding-top: 0px;
        }

        .container {
            max-width: 1308px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ===== Social Share Section (match news page colors) ===== */
        .custom-date-share {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .share-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            position: relative;
        }

        .share-icons {
            display: flex;
            gap: 8px;
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .share-container.active .share-icons {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
        }

        .share-icons a {
            border-radius: 50%;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #929292;
            transition: color 0.3s ease;
        }

        .share-icons a:hover {
            color: #dbdbdbff;
        }

        .share-btn {
            background: transparent;
            border: none;
            border-radius: 0;
            width: auto;
            height: auto;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: none;
        }

        .share-btn:hover {
            background: transparent;
        }

        .share-btn img {
            filter: brightness(0) invert(1);
        }

        /* ===== Writer Card ===== */
        .writer-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #fafafa;
            border: 1px solid #eee;
            margin: 24px auto;
            max-width: 650px;
        }

        .writer-card img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
        }

        .writer-info {
            display: flex;
            flex-direction: column;
            text-align: right;
            color: #555 !important;
        }

        .writer-info .name {
            font-size: 16px;
            color: #555 !important;
            font-family: asswat-bold;
        }

        .writer-info .bio {
            font-size: 16px;
            color: #555 !important;
            font-family: asswat-regular;
        }



        /* ==================== HEADER STYLES ==================== */
        /* Hero header (scrolls away with hero) */
        header {
            background: transparent;
            backdrop-filter: none;
            color: #2c3e50;
            padding: 12px 0;
            position: absolute;
            /* inside hero, not fixed to viewport */
            width: 100%;
            z-index: 2002;
            top: 0;
            left: 0;
            box-shadow: none;
        }

        header.scrolled {
            background: transparent;
            backdrop-filter: none;
            padding: 12px 0;
        }

        /* ==================== SECONDARY NAV (only when scrolling up past hero) ==================== */
        .scroll-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #252525;
            /* solid white */
            padding: 14px 0;
            z-index: 2002;
            transform: translateY(-110%);
            /* hidden slightly above */
            opacity: 0;
            pointer-events: none;
        }

        .scroll-navbar.visible {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo img {
            max-height: 40px;
            width: auto;
            display: block;
        }

        .logo-default {
            display: block !important;
        }

        .logo-scrolled {
            display: none !important;
        }

        .scroll-navbar .logo-default {
            display: none !important;
        }

        .scroll-navbar .logo-scrolled {
            display: block !important;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
            flex: 1;
            justify-content: center;
            margin: 0;
            align-items: center;
        }

        nav li {
            margin: 0;
            display: flex;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.5px;
            position: relative;
        }

        nav a:hover {
            color: #fff;
            /* keep text white */
            font-weight: 700;
            /* bold emphasis instead of gradient underline */
        }

        .scroll-navbar nav a {
            color: #fff;
        }

        .scroll-navbar nav a:hover {
            color: #fff;
            font-weight: 700;
        }

        /* Removed gradient underline animation */

        /* Dropdown in scroll navbar */
        .scroll-navbar .scroll-menu {
            position: relative;
        }

        .scroll-navbar .menu-toggle {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            padding: 8px 18px;
            border-radius: 28px;
            font-size: 14px;
            font-family: 'asswat-bold', 'asswat-regular', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2c3e50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .scroll-navbar .menu-toggle:hover {
            background: #f5f7fa;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        .scroll-navbar .menu-toggle i {
            font-size: 16px;
        }

        .scroll-navbar .dropdown-menu {
            position: absolute;
            top: 110%;
            right: 0;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            padding: 12px 0;
            min-width: 240px;
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
            z-index: 1200;
        }

        .scroll-navbar .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .scroll-navbar .dropdown-menu ul {
            flex-direction: column;
            gap: 4px;
        }

        .scroll-navbar .dropdown-menu ul li {
            margin: 0;
        }

        .scroll-navbar .dropdown-menu ul li a {
            color: #2c3e50;
            display: block;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            position: relative;
        }

        .scroll-navbar .dropdown-menu ul li a:hover {
            background: #f5f7fa;
            color: #667eea;
        }

        .scroll-navbar .dropdown-menu ul li.site-nav-link.active a {
            background: #eef1ff;
            color: #667eea;
            font-weight: 700;
        }

        /* Subnav inside dropdown */
        .scroll-navbar .dropdown-menu ul li .subnav-list li a {
            padding: 6px 24px;
            font-size: 13px;
            color: #445566;
            font-family: 'asswat-regular', sans-serif;
        }

        .scroll-navbar .dropdown-menu ul li .subnav-list li a:hover {
            background: #f0f3f7;
            color: #667eea;
        }

        .scroll-navbar .dropdown-menu ul li .subnav-list {
            border-top: 1px solid #f1f1f1;
            margin-top: 8px;
            padding-top: 6px;
        }

        .scroll-navbar .dropdown-menu ul li a.has-sub {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .scroll-navbar .dropdown-menu ul li a.has-sub.open .sub-arrow {
            transform: rotate(180deg);
        }

        /* Scroll navbar subnav styling */
        .scroll-navbar .site-nav-link {
            position: relative;
        }

        .scroll-navbar .site-nav-link .subnav-list {
            position: absolute;
            top: 100%;
            right: 0;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 0;
            min-width: 250px;
            list-style: none;
            margin: 8px 0 0;
            display: none;
            z-index: 50;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
            flex-direction: column;
            gap: 0;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .scroll-navbar .site-nav-link .subnav-list li a {
            display: block;
            padding: 8px 18px;
            font-size: 16px;
            color: #2c3e50;
            font-family: 'asswat-regular', sans-serif;
            width: 100%;
            box-sizing: border-box;
        }

        .scroll-navbar .site-nav-link .subnav-list li:last-child a {
            border-right: none;
        }

        .scroll-navbar .site-nav-link .subnav-list li a:hover {
            color: #2c3e50;
            font-weight: 700;
            background: #e8e8e8;
        }

        .scroll-navbar .site-nav-link>a.has-sub {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .scroll-navbar .site-nav-link>a.has-sub .sub-arrow {
            font-size: 11px;
        }

        .scroll-navbar .site-nav-link>a.has-sub.open .sub-arrow {
            transform: rotate(180deg);
        }

        /* Active state for inline hero nav */
        header .site-nav-links {
            gap: 20px;
        }

        header .site-nav-link a {
            padding: 4px 6px;
        }

        header .site-nav-link.active a {
            color: #fff;
            font-weight: 700;
        }

        /* Hero header subnav dropdown (inline) */
        header .site-nav-link {
            position: relative;
        }

        header .site-nav-link .subnav-list {
            position: absolute;
            top: 100%;
            right: 0;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 0;
            min-width: 250px;
            list-style: none;
            margin: 8px 0 0;
            display: none;
            z-index: 50;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
            flex-direction: column;
            gap: 0;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        header .site-nav-link .subnav-list li a {
            display: block;
            padding: 8px 18px;
            font-size: 16px;
            color: #2c3e50;
            font-family: 'asswat-regular', sans-serif;
            width: 100%;
            box-sizing: border-box;
        }

        header .site-nav-link .subnav-list li:last-child a {
            border-right: none;
        }

        header .site-nav-link .subnav-list li a:hover {
            background: #e8e8e8;
            color: #2c3e50;
            font-weight: 700;
        }

        header .site-nav-link>a.has-sub {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 100%;
        }

        header .site-nav-link>a.has-sub .sub-arrow {
            transition: transform .25s ease;
            font-size: 11px;
        }

        header .site-nav-link>a.has-sub.open .sub-arrow {
            transform: rotate(180deg);
        }

        /* ==================== HERO SECTION ==================== */
        .split-hero {
            display: flex;
            height: 100vh;
            position: relative;
            overflow: hidden;
            margin-top: 0;
        }

        .hero-image {
            flex: 1;
            display: flex;
            align-items: stretch;
            justify-content: stretch;
            overflow: hidden;
            position: relative;
        }


        .hero-image img {
            width: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.175);
            color: #fff;
            font-size: 14px;
            padding: 12px 20px;
            margin: 0;
            text-align: right;
            direction: rtl;
            font-family: asswat-regular;
            z-index: 3;
            line-height: 1.5;
        }

        .hero-content {
            flex: 1;
            background: #252525;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0;
            position: relative;
            z-index: 3;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 48px;
            font-weight: 600;
            font-family: 'asswat-bold', 'asswat-regular', sans-serif;
            line-height: 1.2;
            position: relative;
            z-index: 1;
            letter-spacing: -1px;
            max-width: 100%;
            padding: 40px 60px;
        }

        .hero-content p {
            font-size: 18px;
            font-family: 'ADJ-Effra';
            color: rgb(130, 130, 130);
            margin-bottom: 35px;
            max-width: 90%;
            padding: 0 20px;
            position: relative;
            z-index: 1;
            line-height: 1.7;
        }

        .actor-info {
            position: relative;
            z-index: 1;
            padding-left: 20px;
            padding-right: 20px;
        }

        .actor-name {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .actor-films {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .article-date {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.75);
            font-family: asswat-light;
            margin-top: 10px;
        }

        /* ==================== CONTENT SECTION ==================== */
        .content {
            padding: 20px 0;
            background-color: inherit;
        }

        .intro {
            max-width: 700px;
            margin: 0 auto 60px;
            font-size: 20px !important;
            font-family: asswat-regular;
            color: #000000;
            line-height: 31px;
            font-weight: normal;
            text-align: justify;
            margin-bottom: 30px;
        }

        .intro * {
            font-family: asswat-regular !important;
            direction: rtl !important;
            box-sizing: border-box;
        }

        .intro p {
            margin-bottom: 15px;
        }

        .intro h2,
        .intro h4 {
            font-family: asswat-bold !important;
            color: #111 !important;
            text-align: right !important;
            /* Bottom margin deliberately much smaller than the top: a heading
               belongs to the text that follows it, not to the block above. */
            margin-top: 40px !important;
            margin-bottom: 16px !important;
            font-size: 32px !important;
        }

        .intro h3 {
            color: #333 !important;
            margin: 32px 0 12px !important;
        }

        .intro img {
            display: block;
            max-width: 100% !important;
            height: auto !important;
        }

        /* Expand icon on content images */
        .content-img-wrap {
            position: relative;
            display: block;
            width: fit-content;
            max-width: 100%;
        }

        .content-img-wrap img {
            cursor: pointer;
        }

        .content-img-expand {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            border-radius: 50%;
            padding: 8px;
            font-size: 22px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .content-img-expand:hover {
            background: rgba(0, 0, 0, 0.95);
        }

        .intro a {
            color: #000000;
            text-decoration: none;
            position: relative;
            border-bottom: 2px solid #e4f0ef;
            border-radius: 0;
            padding-bottom: 1px;
            display: inline;
            transition: color 0.3s ease;
        }

        .intro a::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 0;
            background-color: #e4f0ef;
            z-index: -1;
            transition: height 0.3s ease;
            border-radius: 0;
        }

        .intro a:hover {
            color: #000000;
        }

        .intro a:hover::before {
            height: calc(100% + 2px);
        }

        .intro figure {
            width: 100%;
            margin: 25px 0;
        }

        .intro figure img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .intro figcaption {
            background: #F5F5F5;
            color: #555;
            font-size: 15px;
            padding: 10px;
            text-align: right;
            font-family: asswat-regular;
            direction: rtl;
        }

        .intro figcaption:empty {
            display: none;
        }

        .intro video {
            width: 100%;
            height: auto;
        }

        .intro iframe[src*="youtube"],
        .intro iframe[src*="vimeo"],
        .intro iframe[src*="dailymotion"] {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            border: none;
        }

        .intro audio {
            width: 100% !important;
            height: 50px !important;
            margin: 25px 0;
            display: block;
            border-radius: 25px;
            background: #f5f5f5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .intro blockquote {
            width: 100%;
            padding: 40px 20px;
            margin: 30px 0;
            text-align: center;
            position: relative;
            font-family: asswat-medium;
        }

        .intro blockquote p {
            font-size: 28px;
            color: #222;
            line-height: 1.6;
            font-family: asswat-bold !important;
            text-align: center !important;
        }

        .intro blockquote p span {
            font-size: 28px;
            color: #222;
            line-height: 1.6;
            font-family: asswat-bold !important;
            text-align: center !important;
        }

        .intro blockquote::before {
            content: "";
            position: absolute;
            top: 0px;
            right: 0px;
            width: 32px;
            height: 32px;
            background: url('/user/assets/icons/up.png') no-repeat center;
            background-size: contain;
        }

        .intro blockquote::after {
            content: "";
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 32px;
            height: 32px;
            background: url('/user/assets/icons/down.png') no-repeat center;
            background-size: contain;
        }

        /* Clickable text styling */
        .intro p .clickable-term {
            color: #000000;
            text-decoration: none;
            cursor: pointer;
            border-radius: 50%;
            background-color: #e4f0ef;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", Arial, sans-serif !important;
            font-size: 9px !important;
            font-weight: 700;
            vertical-align: super;
            display: inline-grid;
            place-items: center;
            line-height: 1;
            min-width: 16px;
            min-height: 16px;
            aspect-ratio: 1;
            padding: 2px;
            transition: all 0.2s ease;
        }

        .intro p .clickable-term:hover {
            background-color: #d0e8e5;
            transform: scale(1.1);
        }

        /* X (Twitter) Embed Styles in intro */
        .intro .twitter-tweet {
            margin: 20px auto !important;
            max-width: 100% !important;
        }

        .intro .twitter-tweet iframe {
            max-width: 100% !important;
        }

        /* Prevent blockquote decorative icons on twitter-tweet embeds */
        .intro blockquote.twitter-tweet::before,
        .intro blockquote.twitter-tweet::after {
            display: none !important;
        }

        /* Reset blockquote styling for twitter-tweet embeds */
        .intro blockquote.twitter-tweet {
            padding: 0 !important;
            margin: 20px auto !important;
            text-align: initial !important;
            font-family: inherit !important;
        }

        .intro blockquote.twitter-tweet p {
            font-size: inherit !important;
            font-family: inherit !important;
            text-align: initial !important;
        }

        /* Instagram Embed Styles in intro */
        .intro .instagram-media {
            margin: 20px auto !important;
            width: 100% !important;
            max-width: 540px !important;
        }

        /* ==================== FILM LIST STYLES ==================== */
        .film-list {
            counter-reset: film-counter;
            max-width: 950px;
        }

        .film-item {
            display: flex;
            margin-bottom: 20px;
            position: relative;
            gap: 40px;
            padding: 20px 40px;
            border-radius: 0;
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .film-poster-link {
            display: block;
            text-decoration: none;
        }

        .film-title-link {
            text-decoration: none;
            color: inherit;
        }

        /* Left side: Photo and button */
        .film-left {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .film-poster {
            width: 215px;
            border-radius: 0;
            overflow: hidden;
            aspect-ratio: 3 / 4;
        }

        .film-poster img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        /* Right side: Content */
        .film-right {
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .film-number {
            font-size: 55px;
            color: rgb(205, 205, 203);
            margin-bottom: 10px;
            line-height: 1;
            font-family: 'asswat-bold'
        }

        .film-number::before {
            content: counter(film-counter);
            counter-increment: film-counter;
        }

        .film-writer {
            font-size: 14px;
            color: #888;
            margin-bottom: 12px;
            font-weight: 500;
            font-family: 'asswat-regular', sans-serif;
        }

        .film-writer.hidden {
            display: none;
        }

        .film-title {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 700;
            font-family: 'asswat-medium', sans-serif;
            color: #000000;
        }

        .film-description {
            font-size: 16px !important;
            line-height: 1.625rem !important;
            text-align: right !important;
            color: #000000 !important;
            font-weight: normal;
            font-family: asswat-regular !important;
            direction: rtl !important;
            position: relative;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .film-description p {
            margin-bottom: 15px;
        }

        .film-description p:last-child {
            margin-bottom: 0;
        }

        .film-description.expanded {
            max-height: none;
            overflow: visible;
        }

        .film-description a {
            color: #000000;
            text-decoration: none;
            position: relative;
            border-bottom: 2px solid #e4f0ef;
            border-radius: 0;
            padding-bottom: 1px;
            display: inline;
            transition: color 0.3s ease;
        }

        .film-description a::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 0;
            background-color: #e4f0ef;
            z-index: -1;
            transition: height 0.3s ease;
            border-radius: 0;
        }

        .film-description a:hover {
            color: #000000;
        }

        .film-description a:hover::before {
            height: calc(100% + 2px);
        }

        .film-description p .clickable-term {
            color: #000000;
            text-decoration: none;
            cursor: pointer;
            border-radius: 50%;
            background-color: #e4f0ef;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", Arial, sans-serif !important;
            font-size: 9px !important;
            font-weight: 700;
            vertical-align: super;
            display: inline-grid;
            place-items: center;
            line-height: 1;
            min-width: 16px;
            min-height: 16px;
            aspect-ratio: 1;
            padding: 2px;
            transition: all 0.2s ease;
        }

        .film-description p .clickable-term:hover {
            background-color: #d0e8e5;
            transform: scale(1.1);
        }

        /* X (Twitter) Embed Styles */
        .film-description .twitter-tweet {
            margin: 20px auto !important;
            max-width: 100% !important;
        }

        .film-description .twitter-tweet iframe {
            max-width: 100% !important;
        }

        /* Instagram Embed Styles */
        .film-description .instagram-media {
            margin: 20px auto !important;
            width: 100% !important;
            max-width: 540px !important;
        }

        .film-description-wrapper {
            position: relative;
        }

        .expand-description-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 12px 0;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #000000;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: row-reverse;
            align-items: center;
            gap: 8px;
            font-weight: 400;
            font-family: asswat-regular;
            width: 100%;
            justify-content: flex-start;
            position: relative;
        }

        .expand-description-btn::before {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 14px;
            flex-shrink: 0;
            transform: rotate(0deg);
            transition: transform 0.3s ease;
        }

        .expand-description-btn.expanded::before {
            transform: rotate(180deg);
        }

        .expand-description-btn::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        .expand-description-btn-text {
            display: none;
        }

       

       

        .film-metadata {
            display: flex;
            gap: 25px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            font-size: 14px;
            color: #666;
            flex-wrap: wrap;
        }

        .film-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .rating {
            color: #ffc107;
            font-weight: 700;
        }

        /* ==================== TAGS STYLING ==================== */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 24px auto;
            max-width: 650px;
        }

        .tag-item {
            display: inline-block;
            background: #f1f1f1;
            color: #000;
            font-size: 14px;
            padding: 6px 14px;
            font-family: asswat-medium;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
        }

        .tag-item:hover {
            background: #e0e0e0;
            color: #000;
        }

        /* ==================== READ MORE CARD STYLING ==================== */
        .read-more-block {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 12px;
            direction: rtl;
            margin: 28px auto;
            max-width: 650px;
            width: 100%;
            box-sizing: border-box;
            background: #F5F5F5;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
            border-radius: 0;
        }

        .read-more-category {
            display: none !important;
        }

        .read-more-placeholder {
            display: block;
            text-align: center;
            color: #999;
            font-size: 14px;
            padding: 1rem;
        }

        .read-more-block:has(.read-more-content) .read-more-placeholder {
            display: none;
        }

        .read-more-label-text {
            font-size: 18px;
            font-family: asswat-bold;
            color: #666;
            display: flex;
            align-items: center;
            white-space: nowrap;
            order: -1;
        }

        .read-more-image {
            width: 160px;
            height: 90px;
            aspect-ratio: 16/9;
            object-fit: cover;
            flex-shrink: 0;
            display: block;
            position: relative;
        }

        .read-more-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            justify-content: center;
            padding: 0 8px;
        }

        .read-more-category {
            font-size: 12px;
            font-family: asswat-regular;
            color: #888;
            text-align: right;
            margin: 0;
        }

        .read-more-title {
            font-size: 18px;
            font-family: asswat-medium !important;
            color: #000000;
            margin-top: 2px !important;
            margin-bottom: 2px !important;
            line-height: 1.3;
            text-align: right;
        }

        .read-more-summary {
            display: none;
        }

        /* ==================== BACK TO TOP BUTTON ==================== */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            z-index: 999;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* ===== Related (ذات صلة) grid ===== */
        .economy-grid-container-news {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .economy-card-news {
            display: flex;
            flex-direction: column;
        }

        .economy-card-news img {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
        }

        .economy-card-news h3 {
            font-size: 12px;
            margin: 8px 0 4px;
            color: #74747C;
            font-family: asswat-light;
            font-weight: lighter;
        }

        .economy-card-news h2 {
            font-size: 16px;
            margin: 0;
            font-family: asswat-bold;
            color: #333;
        }

        .economy-card-news p {
            font-size: 14px;
            color: #555;
        }

        /* Related news: horizontal scroll on mobile (like news.blade) */
        @media (max-width: 991px) {
            .economy-grid-container-news {
                display: flex;
                grid-template-columns: none;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
                gap: 14px;
                direction: rtl;
            }

            .economy-grid-container-news::-webkit-scrollbar {
                display: none;
            }

            .economy-card-news {
                flex: 0 0 72vw;
                scroll-snap-align: start;
            }

            .economy-card-news h3 {
                font-size: 14px;
            }

            .economy-card-news h2 {
                font-size: 18px;
            }
        }

        .section-title {
            font-size: 20px;
            font-family: 'asswat-medium' !important;
            font-weight: 400 !important;
            color: #000;
            text-align: right;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        /* ==================== FOOTER STYLES ==================== */
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #1a2332 100%);
            color: #fff;
            padding: 50px 0 20px;
            text-align: center;
            font-size: 14px;
        }

        .footer-links {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .footer-links a:hover {
            color: #667eea;
        }

        .copyright {
            color: rgba(255, 255, 255, 0.6);
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ==================== RESPONSIVE STYLES ==================== */

        /* Hide mobile content on web/desktop */
        @media (min-width: 769px) {

            .mobile-navbar,
            .mobile-sidebar,
            .sidebar-overlay {
                display: none !important;
            }

            /* Position share at bottom-left of grey hero area on desktop */
            .hero-content .custom-date-share {
                position: absolute;
                bottom: 24px;
                left: 53px;
                margin: 0;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 56px;
            }

            body.has-admin-bar {
                padding-top: 0px;
            }

            .admin-top-bar {
                display: none !important;
            }

            .split-hero {
                flex-direction: column;
                height: auto;
                margin-top: 0;
            }

            header {
                display: none !important;
            }

            .scroll-navbar {
                display: none !important;
            }

            .hero-image {
                height: 50vh;
                min-height: 300px;
                margin-top: 25px;
            }

            .hero-caption {
                font-size: 12px;
                padding: 10px 15px;
            }

            .hero-content {
                padding: 30px 20px;
            }

            .hero-content h1 {
                padding: 35px 10px 10px 10px;
                font-size: 32px;
                margin-bottom: 15px;
            }

            .hero-content p {
                font-size: 14px;
                margin-bottom: 20px;
            }

            .actor-info {
                margin-top: 20px;
                padding-top: 20px;
            }

            .actor-name {
                font-size: 16px;
            }

            .content {
                padding: 20px 0;
            }

            .intro {
                font-size: 15px !important;
                padding: 0 15px;
                margin-bottom: 40px;
                font-family: asswat-regular;
                color: #000000;
                line-height: 1.9;
                text-align: right;
            }

            .film-list {
                max-width: 100%;
                padding: 0 15px;
            }

            .film-item {
                flex-direction: column;
                padding: 20px;
                margin-bottom: 30px;
                gap: 20px;
                box-shadow: none;
            }

            .film-left {
                width: 100%;
                align-items: center;
            }

            .film-poster {
                width: 100%;
                max-width: 280px;
            }

            .film-right {
                width: 100%;
            }

            .film-number {
                font-size: 36px;
                text-align: center;
            }

            .film-writer {
                text-align: center;
                font-size: 13px;
            }

            .film-title {
                font-size: 22px;
                text-align: center;
            }

            .film-description {
                font-size: 14px;
                line-height: 1.7;
                max-height: calc(1.7em * 9);
            }

            .expand-description-btn {
                margin-top: 8px;
                font-size: 14px;
            }

            .expand-description-btn::after {
                font-size: 12px;
            }

            .film-description a::before {
                height: 1px;
            }

            .back-to-top {
                bottom: 20px;
                left: 20px;
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }

        /* ==================== TEXT DEFINITION MODAL STYLES ==================== */
        .text-definition-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            animation: modalBackdropFadeIn 0.3s ease;
        }

        @keyframes modalBackdropFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .text-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            animation: backdropFadeIn 0.3s ease;
        }

        @keyframes backdropFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .text-modal-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            width: 100%;
            max-height: 80vh;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            animation: modalSlideUp 0.4s ease-out;
            overflow: hidden;
        }

        @keyframes modalSlideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        @keyframes modalSlideDown {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(100%);
            }
        }

        .text-modal-container.closing {
            animation: modalSlideDown 0.3s ease-out forwards;
        }

        .text-definition-modal.closing .text-modal-backdrop {
            animation: backdropFadeOut 0.3s ease forwards;
        }

        @keyframes backdropFadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .text-modal-header {
            padding: 0;
            border-bottom: none;
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            background: white;
            border-radius: 0;
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 10;
        }

        .text-modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #000;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .text-modal-close:hover {
            color: #000;
            transform: scale(1.1);
        }

        .text-modal-body {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
            animation: bodyFadeIn 0.4s;
            display: flex;
            flex-direction: row;
            gap: 2rem;
            align-items: flex-start;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        @keyframes bodyFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .text-modal-title {
            font-size: 16px;
            font-weight: bold;
            color: #222;
            font-family: asswat-bold;
            text-align: right;
            direction: rtl;
            margin-bottom: 8px;
            width: 100%;
        }

        .text-modal-body p {
            margin: 0;
            color: #444;
            font-family: asswat-regular;
            font-size: 16px;
            line-height: 1.8;
            text-align: right;
            direction: rtl;
        }

        #textModalImageContainer {
            margin-bottom: 0;
            flex-shrink: 0;
            flex-grow: 0;
            order: 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        #textModalImage {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 150px;
            object-fit: contain;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        #textModalImage:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .text-modal-body {
                flex-direction: column;
                gap: 1rem;
            }

            #textModalImageContainer {
                width: 100%;
                order: -1;
            }

            .text-modal-title {
                margin-top: 2rem;
            }
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .film-item {
            animation: fadeIn 0.6s ease forwards;
        }

        .film-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .film-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .film-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .film-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        .film-item:nth-child(5) {
            animation-delay: 0.5s;
        }

        .film-item:nth-child(6) {
            animation-delay: 0.6s;
        }

        .film-item:nth-child(7) {
            animation-delay: 0.7s;
        }

        .film-item:nth-child(8) {
            animation-delay: 0.8s;
        }

        .film-item:nth-child(9) {
            animation-delay: 0.9s;
        }

        .film-item:nth-child(10) {
            animation-delay: 1s;
        }

        /* ==================== MOBILE NAVIGATION STYLES ==================== */
        @media (max-width: 768px) {
            .mobile-navbar {
                display: block !important;
                background-color: #ffffff;
                border-bottom: 1px solid #e0e0e0;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1500;
                width: 100%;
            }

            /* Closed (white bar): show the web logo; hide the white one */
            .mobile-navbar .logo-white {
                display: none;
            }

            .navbar-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 16px;
            }

            .navbar-logo a {
                display: flex;
                align-items: center;
            }

            .logo-img {
                height: 40px;
                width: auto;
            }

            .navbar-icons {
                display: flex;
                align-items: center;
            }

            .menu-toggle {
                background: none;
                border: none;
                cursor: pointer;
                padding: 8px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                position: relative;
                min-width: 44px;
                min-height: 44px;
            }

            .hamburger-icon {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }

            .line {
                width: 20px;
                height: 2px;
                background-color: #252525;
                transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.3s ease;
                border-radius: 2px;
            }

            /* Same button morphs into an X (menu <-> close) with a transition */
            .menu-toggle.active .line-1 {
                transform: translateY(7px) rotate(45deg);
            }

            .menu-toggle.active .line-2 {
                opacity: 0;
            }

            .menu-toggle.active .line-3 {
                transform: translateY(-7px) rotate(-45deg);
            }

            /* Sidebar open (dark panel): grey navbar with white logo + close (X) */
            body:has(.mobile-sidebar.active) .mobile-navbar {
                background-color: #252525;
                border-bottom-color: transparent;
            }

            body:has(.mobile-sidebar.active) .mobile-navbar .logo-web {
                display: none;
            }

            body:has(.mobile-sidebar.active) .mobile-navbar .logo-white {
                display: block;
            }

            body:has(.mobile-sidebar.active) .mobile-navbar .line {
                background-color: #ffffff;
            }

            .mobile-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background-color: #252525;
                transition: left 0.5s ease;
                z-index: 1400;
                padding-top: 56px;
                overflow-y: auto;
            }

            .mobile-sidebar.active {
                left: 0;
            }

            .sidebar-content {
                width: 100%;
            }

            .menu-list {
                list-style: none;
                margin: 0;
                padding: 12px 0;
            }

            .menu-list>li>.menu-item-header,
            .menu-list>li>a {
                border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            }

            .menu-list>li:last-child>.menu-item-header,
            .menu-list>li:last-child>a {
                border-bottom: none;
            }

            .menu-list a {
                display: block;
                padding: 16px 20px;
                color: #ffffff;
                text-decoration: none;
                font-size: 20px;
                font-family: 'asswat-bold', 'asswat-regular';
                transition: all 0.2s ease;
            }

            .menu-item-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .menu-item-header a {
                flex: 1;
                padding: 16px 20px;
                border-bottom: none;
            }

            .submenu-toggle {
                background: none;
                border: none;
                padding: 0;
                cursor: pointer;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
            }

            .submenu-toggle .toggle-arrow {
                transition: transform 0.3s ease;
                font-size: 16px;
            }

            .submenu-toggle.active .toggle-arrow {
                transform: rotate(90deg);
            }

            .submenu {
                list-style: none;
                margin: 0;
                padding: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
                background: rgba(0, 0, 0, 0.2);
            }

            .submenu.active {
                max-height: 600px;
            }

            .submenu a {
                font-size: 16px;
                padding: 12px 20px 12px 40px;
                font-family: 'asswat-regular';
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                z-index: 1300;
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        /* === Post titles ===
           Same rule as public/user/css/main.css. Repeated here because this
           template is a standalone document that does not load main.css. The
           doubled [href] outranks per-component rules such as
           `.economy-card-news h2 { color: #333 }`. */
        a[href*="/article/"][href] h1,
        a[href*="/article/"][href] h2,
        a[href*="/article/"][href] h3,
        a[href*="/article/"][href] h4,
        a[href*="/article/"][href] p {
            color: #000000 !important;
        }

    </style>
</head>

<body class="{{ Auth::check() ? 'has-admin-bar' : '' }}">

    <!-- Mobile Navigation (visible only on mobile) -->
    <nav class="mobile-navbar" id="mobileNavbar" style="display: none;">
        <div class="navbar-content">
            <div class="navbar-logo">
                <a href="{{ route('index') }}">
                    <img loading="lazy" decoding="async" src="{{ asset('user/assets/images/logo.svg') }}" alt="Logo" class="logo-img logo-web">
                    <img loading="lazy" decoding="async" src="{{ asset('user/assets/images/white_logo.svg') }}" alt="Logo" class="logo-img logo-white">
                </a>
            </div>
            <div class="navbar-icons">
                <button class="menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
                    <span class="hamburger-icon">
                        <span class="line line-1"></span>
                        <span class="line line-2"></span>
                        <span class="line line-3"></span>
                    </span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-content">
            <ul class="menu-list">
                <li class="menu-item-with-submenu">
                    <div class="menu-item-header">
                        <a href="{{ route('latestNews') }}">أخبار</a>
                        <button class="submenu-toggle" aria-label="Toggle submenu">
                            <i class="fa-solid fa-chevron-left toggle-arrow" aria-hidden="true"></i>
                        </button>
                    </div>
                    <ul class="submenu" id="newsSubmenu">
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
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    @if (Auth::check())
        <div class="admin-top-bar">
            <div class="admin-bar-content">
                <span>
                    <i class="fas fa-user"></i>
                    {{ Auth::user()->name }}
                </span>

                <div class="admin-actions">
                    <a target="_blank" href="{{ route('dashboard.index') }}" title="لوحة التحكم">
                        <i class="fas fa-gauge"></i>
                    </a>
                    @if (isset($news))
                        <a href="{{ route('dashboard.content.edit', $news->id) }}" class="btn btn-sm btn-warning"
                            title="تعديل">
                            <i class="fas fa-pencil"></i>
                        </a>
                    @endif

                    <a href="{{ route('dashboard.content.create') }}" class="btn btn-sm btn-warning" title="إضافة خبر">
                        <i class="fa-solid fa-plus"></i>
                    </a>

                    <a href="{{ route('dashboard.breakingnew.create') }}" class="admin-action-breaking"
                        title="إضافة عاجل">
                        <i class="fa-solid fa-plus"></i>
                    </a>

                    <a href="{{ route('dashboard.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        title="تسجيل الخروج">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                    </a>
                    <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST"
                        style="display:none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <style>
            /* Horizontal rule inserted via TinyMCE inside article content */
            .intro hr {
                border: none;
                height: 1px;
                background-color: #cacaca;
                margin: 24px 0;
            }

            .admin-top-bar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 36px;
                background: #F5F5F5;
                color: #333;
                font-size: 14px;
                z-index: 4000;
                display: flex;
                align-items: center;
            }

            .admin-bar-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 1320px;
                max-width: 100%;
                margin: 0 auto;
                padding: 0 20px;
                box-sizing: border-box;
            }

            .admin-bar-content span {
                font-family: asswat-bold;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .admin-actions a {
                color: #333;
                margin-right: 15px;
                text-decoration: none;
                transition: color 0.2s;
                font-family: asswat-bold;
                font-size: 16px;
            }

            .admin-actions a:hover {
                color: #333;
            }

            .admin-action-breaking i {
                color: #dc3545;
                font-size: 16px;
            }

            .admin-action-breaking:hover i {
                color: #c82333;
            }

            .split-hero {
                margin-top: 36px;
            }

            header {
                top: 0;
            }
        </style>
    @endif

    <section class="split-hero">
        <header id="main-header">
            <div class="container">
                <div class="header-content">
                    <a href="/" class="logo">
                        <img class="logo-default" src="{{ asset('user/assets/images/white_logo.svg') }}" alt="Logo"
                            style="height: 40px;">
                        <img class="logo-scrolled" src="{{ asset('user/assets/images/white_logo.svg') }}"
                            alt="Logo" style="height: 40px; display: none;">
                    </a>
                    <div class="theme-hero-actions">
                        <form action="{{ route('search') }}" method="GET" class="theme-hero-search">
                            <input name="query" type="text" class="theme-hero-search-input" placeholder="ابحث...">
                            <button type="submit" class="theme-hero-search-btn" aria-label="بحث">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                        <button type="button" class="theme-hero-menu-btn" data-theme-menu-open
                            aria-label="القائمة" aria-expanded="false" aria-controls="themeMenuPanel">
                            <span class="material-symbols-outlined">dehaze</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <div class="hero-image">
            <img loading="lazy" src="{{ $news->media()->wherePivot('type', 'detail')->first()->path }}" alt="{{ $news->title }}">
            @if ($news->caption)
                <figcaption class="hero-caption">{{ $news->caption }}</figcaption>
            @endif
        </div>
        <div class="hero-content">
            {{-- Category --}}
            <div class="custom-category">
                @if ($news->category && $news->country)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}"
                        style="color: #888; text-decoration: none;">
                        {{ $news->category->name ?? '' }}
                    </a>
                    -
                    <a href="{{ route('category.show', ['id' => $news->country->id, 'type' => 'Country']) }}"
                        style="color: #888; text-decoration: none;">
                        {{ $news->country->name ?? '' }}
                    </a>
                @elseif ($news->category && $news->continent)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}"
                        style="color: #888; text-decoration: none;">
                        {{ $news->category->name ?? '' }}
                    </a>
                    -
                    <a href="{{ route('category.show', ['id' => $news->continent->id, 'type' => 'Continent']) }}"
                        style="color: #888; text-decoration: none;">
                        {{ $news->continent->name ?? '' }}
                    </a>
                @elseif ($news->category)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}"
                        style="color: #888; text-decoration: none;">
                        {{ $news->category->name ?? '' }}
                    </a>
                @endif
            </div>
            <h1>{{ $news->long_title }}</h1>
            <p class="article-desc">{{ $news->summary }}</p>

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

                $dateToUse = null;
                if ($news->published_date) {
                    $dateToUse = $news->published_date;
                } elseif ($news->published_at) {
                    $dateToUse = $news->published_at;
                } else {
                    $dateToUse = $news->created_at;
                }

                if (is_string($dateToUse)) {
                    $date = \Carbon\Carbon::parse($dateToUse);
                } else {
                    $date = $dateToUse;
                }

                $day = $date->format('d');
                $month = $months[$date->format('m')];
                $year = $date->format('Y');
                $time = $date->format('H:i');
            @endphp

            <div class="actor-info">
                <div class="actor-name">
                    @if ($news->city?->name && $news->writers->first()?->name)
                        {{ $news->city->name }} - <a href="{{ route('writer.show', $news->writers->first()->id) }}" style="text-decoration: none; color: inherit; cursor: pointer;">{{ $news->writers->first()?->name }}</a>
                    @elseif ($news->city?->name)
                        
                    @elseif ($news->writers->first()?->name)
                        <a href="{{ route('writer.show', $news->writers->first()->id) }}" style="text-decoration: none; color: inherit; cursor: pointer;">{{ $news->writers->first()?->name }}</a>
                    @endif
                </div>
                <div class="article-date">{{ $day }} {{ $month }} {{ $year }} | {{ $time }}</div>
            </div>

            @php
                $shareTitle = $news->share_title ?: $news->long_title;
                $shareDescription = $news->share_description ?: $news->summary;
            @endphp

            <div class="custom-date-share">
                <div class="share-container" id="shareContainer">
                    <div class="share-icons">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                            target="_blank" title="مشاركة على فيسبوك" rel="noopener" class="share-icon">
                            <i class="fa-brands fa-facebook"></i>
                        </a>

                        {{-- X (Twitter) --}}
                        <a href="https://x.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($shareTitle . ' - ' . $shareDescription) }}"
                            target="_blank" title="مشاركة على X" rel="noopener" class="share-icon">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($shareTitle . ' - ' . $shareDescription . ' ' . request()->fullUrl()) }}"
                            target="_blank" title="مشاركة على واتساب" rel="noopener" class="share-icon">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                        {{-- Copy Link --}}
                        <a href="#" id="copyLinkBtn" title="نسخ الرابط" rel="noopener" class="share-icon">
                            <i class="fa-solid fa-link"></i>
                        </a>
                    </div>

                    {{-- Share Button --}}
                    <button class="share-btn" id="shareToggle" type="button" title="مشاركة"
                        aria-label="زر المشاركة">
                        <img loading="lazy" decoding="async" src="{{ asset('user/assets/icons/send.png') }}" alt="Share" style="width:20px;">
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">

            <div class="intro custom-article-content">
                {!! $news->content !!}
            </div>

            @include('user.partials.content-enhancements')

            <!-- Secondary navbar (appears only when scrolling UP past hero) -->
            <nav id="scroll-navbar" class="scroll-navbar">
                <div class="container">
                    <div class="header-content">
                        <a href="/" class="logo">
                            <img class="logo-default" src="{{ asset('user/assets/images/white_logo.svg') }}"
                                alt="Logo" style="height: 40px;">
                            <img class="logo-scrolled" src="{{ asset('user/assets/images/white_logo.svg') }}"
                                alt="Logo" style="height: 40px; display: none;">
                        </a>
                        <div class="theme-hero-actions">
                            <form action="{{ route('search') }}" method="GET" class="theme-hero-search">
                                <input name="query" type="text" class="theme-hero-search-input" placeholder="ابحث...">
                                <button type="submit" class="theme-hero-search-btn" aria-label="بحث">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                            <button type="button" class="theme-hero-menu-btn" data-theme-menu-open
                                aria-label="القائمة" aria-expanded="false" aria-controls="themeMenuPanel">
                                <span class="material-symbols-outlined">dehaze</span>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="film-list">
                @foreach ($news->contentLists()->with('writer')->orderBy('index')->get() as $item)
                    <div class="film-item">
                        <div class="film-left">
                            <div class="film-poster">
                                <img loading="lazy" src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="preview-image" data-preview-src="{{ asset($item->image) }}" data-preview-title="{{ $item->title }}" style="cursor: pointer;">
                            </div>
                        </div>
                        <div class="film-right">
                            <div class="film-number"></div>
                            @if ($item->writer()->first())
                                <p class="film-writer"><a href="{{ route('writer.show', $item->writer()->first()->id) }}" style="text-decoration: none; color: inherit; cursor: pointer;">{{ $item->writer()->first()->name }}</a></p>
                            @else
                                <p class="film-writer hidden">كاتب غير معروف</p>
                            @endif
                            @if ($item->url)
                                <a href="{{ $item->url }}" class="film-title-link">
                                    <h2 class="film-title">{{ $item->title }}</h2>
                                </a>
                            @else
                                <h2 class="film-title">{{ $item->title }}</h2>
                            @endif
                            <div class="film-description-wrapper">
                                <div class="film-description">{!! $item->description !!}</div>
                                <button class="expand-description-btn" aria-label="عرض المزيد">
                                    <span class="expand-description-btn-text"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Read-more block(s) are moved out of the article body to here (with the tags) --}}
            <div id="list-readmore-slot"></div>

            {{-- Tags --}}
            @if ($news->tags && count($news->tags) > 0)
                <div class="tags-container">
                    @foreach ($news->tags as $tag)
                        <a href="{{ route('tag.show', $tag->id) }}" class="tag-item">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <script>
                (function () {
                    function moveReadMore() {
                        const slot = document.getElementById('list-readmore-slot');
                        const body = document.querySelector('.intro.custom-article-content');
                        if (!slot || !body) return;
                        body.querySelectorAll('.read-more-block').forEach(function (block) {
                            slot.appendChild(block);
                        });
                    }
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', moveReadMore);
                    } else {
                        moveReadMore();
                    }
                })();
            </script>

            @php
                $writers = $news->writers;
            @endphp

            @if ($writers->count() > 0)
                @foreach ($writers as $writer)
                    @if ($writer->bio != '')
                        <a href="{{ route('writer.show', $writer->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="writer-card">
                                <img loading="lazy" decoding="async" src="{{ $writer->image ?? asset('user.png') }}" alt="{{ $writer->name }}" loading="lazy">
                                <div class="writer-info">
                                    <span class="bio"><span class="name">{{ $writer->name }}</span>
                                        {{ $writer->bio }}</span>
                                </div>
                            </div>
                        </a>
                    @endif
                @endforeach
            @endif

            @if (isset($relatedNews) && $relatedNews->count() > 0)
                <div style="margin-top: 50px;">
                    <p class="section-title">ذات صلة</p>
                    <div style="height: 5px"></div>
                    @include('user.components.ligne')
                    <div style="height: 20px"></div>

                    <div class="economy-grid-container-news">
                        @foreach ($relatedNews as $item)
                            <div class="economy-card-news">
                                <a href="{{ route('news.show', $item->shortlink) }}">
                                    <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? '' }}"
                                        alt="{{ $item->title ?? '' }}" loading="lazy">
                                </a>

                                <h3>
                                    <x-category-links :content="$item" />
                                </h3>

                                <a href="{{ route('news.show', $item->shortlink) }}"
                                    style="text-decoration: none; color: inherit;">
                                    <h2>{{ $item->title ?? '' }}</h2>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Most read (below related) — mobile only --}}
            @include('user.partials.mobile-most-read', ['items' => $lastWeekNews ?? null])

            {{-- More from the category — mobile only --}}
            @include('user.partials.mobile-more-from', ['items' => $lastNews ?? null])
        </div>
    </section>

    <!-- Web Footer (desktop/tablet) -->
    <div class="web-footer">
        @include('user.components.footer')
    </div>

    <!-- Mobile Footer (mobile only) -->
    <div class="mobile-footer">
        @include('user.mobile.footer')
    </div>

    <!-- Back to top button -->
    <div class="back-to-top" id="backToTop">↑</div>

    <style>
        /* Show web footer on desktop/tablet, hide mobile footer */
        .web-footer {
            display: block;
        }

        .mobile-footer {
            display: none;
        }

        @media (max-width: 768px) {

            /* Hide web footer on mobile, show mobile footer */
            .web-footer {
                display: none;
            }

            .mobile-footer {
                display: block;
            }
        }
    </style>

    <script>
        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initHeaderScroll();
            initBackToTop();
            initHeroSubnav();
            initScrollSubnav();
            initMobileMenu();
            initDescriptionToggle();
            initImagePreview();
            initContentImageExpand();
            initializeShareFunctionality();
            initReadMoreClick();
            initializeCopyLink();
        });

        // Hero header subnav logic
        function initHeroSubnav() {
            const heroNewsLink = document.querySelector('#hero-show-subnav > a.has-sub');
            const heroSubnav = document.querySelector('#hero-show-subnav .subnav-list');
            if (!heroNewsLink || !heroSubnav) return;
            heroNewsLink.addEventListener('click', function(e) {
                e.preventDefault();
                const opened = this.classList.contains('open');
                this.classList.toggle('open', !opened);
                this.setAttribute('aria-expanded', (!opened).toString());
                heroSubnav.style.display = opened ? 'none' : 'block';
            });
            // Close if clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!heroSubnav.contains(e.target) && !heroNewsLink.contains(e.target)) {
                    heroSubnav.style.display = 'none';
                    heroNewsLink.classList.remove('open');
                    heroNewsLink.setAttribute('aria-expanded', 'false');
                }
            });
        }
        // Subnav toggle logic for scroll navbar
        function initScrollSubnav() {
            const scrollNewsLink = document.querySelector('#scroll-show-subnav > a.has-sub');
            const scrollSubnav = document.querySelector('#scroll-show-subnav .subnav-list');
            if (!scrollNewsLink || !scrollSubnav) return;
            scrollNewsLink.addEventListener('click', function(e) {
                e.preventDefault();
                const opened = this.classList.contains('open');
                this.classList.toggle('open', !opened);
                this.setAttribute('aria-expanded', (!opened).toString());
                scrollSubnav.style.display = opened ? 'none' : 'block';
            });
            // Close if clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!scrollSubnav.contains(e.target) && !scrollNewsLink.contains(e.target)) {
                    scrollSubnav.style.display = 'none';
                    scrollNewsLink.classList.remove('open');
                    scrollNewsLink.setAttribute('aria-expanded', 'false');
                }
            });
        }
        // Dropdown toggle logic for secondary scroll navbar
        function initDropdownMenu() {
            const toggle = document.querySelector('.scroll-navbar .menu-toggle');
            const menu = document.querySelector('.scroll-navbar .dropdown-menu');
            if (!toggle || !menu) return;

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', (!expanded).toString());
                menu.classList.toggle('show', !expanded);
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                    menu.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    menu.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Subnav (News sections) toggle
            const newsLink = menu.querySelector('#site-show-subnav > a.has-sub');
            const subnavList = menu.querySelector('#site-show-subnav .subnav-list');
            if (newsLink && subnavList) {
                newsLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    const opened = this.classList.contains('open');
                    this.classList.toggle('open', !opened);
                    this.setAttribute('aria-expanded', (!opened).toString());
                    subnavList.style.display = opened ? 'none' : 'block';
                });
            }
        }

        // Scroll logic: show secondary nav only when scrolling UP past hero
        function initHeaderScroll() {
            const heroSection = document.querySelector('.split-hero');
            const scrollNavbar = document.getElementById('scroll-navbar');
            const heroHeight = heroSection ? heroSection.offsetHeight : 0;
            let lastY = window.scrollY;
            const UP_THRESHOLD = 4; // pixels of upward movement before showing
            const DOWN_HIDE_DELAY = 0; // immediate hide on down scroll

            window.addEventListener('scroll', () => {
                const y = window.scrollY;
                const delta = y - lastY;
                const scrollingDown = delta > 0;

                // Hide while inside hero
                if (y < heroHeight) {
                    scrollNavbar.classList.remove('visible');
                } else {
                    if (!scrollingDown && Math.abs(delta) > UP_THRESHOLD) {
                        // Upward scroll beyond threshold past hero
                        scrollNavbar.classList.add('visible');
                    } else if (scrollingDown && Math.abs(delta) > DOWN_HIDE_DELAY) {
                        scrollNavbar.classList.remove('visible');
                    }
                }

                lastY = y;
            });
        }

        // Back to top button functionality
        function initBackToTop() {
            const backToTopBtn = document.getElementById('backToTop');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            });

            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        // Mobile menu functionality
        let mobileMenuInitialized = false;

        function initMobileMenu() {
            if (mobileMenuInitialized) return;

            const menuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const submenuToggle = document.querySelector('.submenu-toggle');
            const submenu = document.getElementById('newsSubmenu');

            if (!menuToggle || !sidebar || !overlay) {
                console.log('Mobile menu elements not found');
                return;
            }

            // Toggle sidebar
            function toggleSidebar() {
                menuToggle.classList.toggle('active');
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            menuToggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Toggle submenu
            if (submenuToggle && submenu) {
                submenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.toggle('active');
                    submenu.classList.toggle('active');
                });
            }

            mobileMenuInitialized = true;
            console.log('Mobile menu initialized');
        }

        // Description expand/collapse functionality (show only first 3 paragraphs by default)
        function initDescriptionToggle() {
            const expandButtons = document.querySelectorAll('.expand-description-btn');

            expandButtons.forEach(button => {
                const description = button.previousElementSibling;
                if (!description) return;

                const paragraphs = Array.from(description.querySelectorAll('p'));

                // If there are 3 or fewer paragraphs, no need for collapse
                if (paragraphs.length <= 3) {
                    button.style.display = 'none';
                    return;
                }

                // Initial collapsed state: show first 3 paragraphs, hide the rest
                paragraphs.forEach((p, index) => {
                    if (index >= 3) {
                        p.style.display = 'none';
                    }
                });

                button.classList.add('collapsed');

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isExpanded = description.classList.contains('expanded');

                    if (isExpanded) {
                        // Collapse back to first 3 paragraphs
                        description.classList.remove('expanded');
                        this.classList.remove('expanded');
                        this.classList.add('collapsed');

                        paragraphs.forEach((p, index) => {
                            if (index >= 3) {
                                p.style.display = 'none';
                            }
                        });
                    } else {
                        // Expand: show all paragraphs
                        description.classList.add('expanded');
                        this.classList.add('expanded');
                        this.classList.remove('collapsed');

                        paragraphs.forEach(p => {
                            p.style.display = '';
                        });
                    }
                });
            });
        }

        /**
         * Initialize Share Functionality
         */
        function initializeShareFunctionality() {
            // Initialize web share (list page uses only desktop container)
            initializeSingleShare('shareContainer', 'shareToggle');
        }

        /**
         * Initialize a single share container
         */
        function initializeSingleShare(containerId, toggleId) {
            const shareContainer = document.getElementById(containerId);
            const shareToggle = document.getElementById(toggleId);

            if (!shareContainer || !shareToggle) {
                return;
            }

            // Toggle share menu on button click
            shareToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                shareContainer.classList.toggle('active');
            });

            // Close share menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!shareContainer.contains(e.target)) {
                    shareContainer.classList.remove('active');
                }
            });

            // Prevent closing when clicking inside share container
            shareContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        /**
         * Initialize Copy Link Functionality
         */
        function initializeCopyLink() {
            // Initialize web copy link
            initializeSingleCopyLink('copyLinkBtn');
        }

        /**
         * Initialize a single copy link button
         */
        function initializeSingleCopyLink(btnId) {
            const copyLinkBtn = document.getElementById(btnId);

            if (!copyLinkBtn) {
                return;
            }

            copyLinkBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const url = window.location.href;

                // Copy to clipboard
                navigator.clipboard.writeText(url).then(function() {
                    // Show success message
                    showCopySuccessMessage(copyLinkBtn);
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                    // Fallback for older browsers
                    fallbackCopyToClipboard(url, copyLinkBtn);
                });
            });
        }

        /**
         * Show Copy Success Message
         */
        function showCopySuccessMessage(element) {
            const originalHTML = element.innerHTML;
            const originalTitle = element.title;

            element.innerHTML = '<i class="fa-solid fa-check"></i>';
            element.title = 'تم نسخ الرابط';

            setTimeout(function() {
                element.innerHTML = originalHTML;
                element.title = originalTitle;
            }, 2000);
        }

        /**
         * Fallback Copy to Clipboard (for older browsers)
         */
        function fallbackCopyToClipboard(text, element) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            showCopySuccessMessage(element);
        }

        /**
         * Initialize ReadMore Block Click Handler
         */
        function initReadMoreClick() {
            document.addEventListener('click', function(e) {
                const card = e.target.closest('.read-more-block');
                if (card) {
                    const shortlink = card.dataset.shortlink;
                    if (shortlink) {
                        e.preventDefault();
                        window.location.href = '/article/' + shortlink;
                    }
                }
            });

            // Keyboard support for read more cards
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    const card = e.target.closest('.read-more-block');
                    if (card) {
                        const shortlink = card.dataset.shortlink;
                        if (shortlink) {
                            e.preventDefault();
                            window.location.href = '/article/' + shortlink;
                        }
                    }
                }
            });
        }

        // Initialize readmore on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initReadMoreClick);
        } else {
            initReadMoreClick();
        }
    </script>

    {{-- ================= TEXT DEFINITION MODAL ================= --}}
    <div id="textDefinitionModal" class="text-definition-modal" style="display: none;" role="dialog"
        aria-labelledby="textModalTitle">
        <div class="text-modal-backdrop"></div>
        <div class="text-modal-container">
            <div class="text-modal-header">
                <button class="text-modal-close" id="textModalCloseBtn" type="button" aria-label="إغلاق">×</button>
            </div>
            <div class="text-modal-body">
                <div id="textModalImageContainer" style="display: none;">
                    <img loading="lazy" decoding="async" id="textModalImage" src="" alt="صورة التعريف">
                </div>
                <div>
                    <h3 id="textModalTitle" class="text-modal-title"></h3>
                    <p id="textModalContent"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * Initialize Text Definition Modal
         */
        function initializeTextDefinitionModal() {
            const textDefinitions = {};

            // Helper to safely decode URI-encoded attribute values
            function safeDecodeAttr(val) {
                if (!val) return '';
                try { return decodeURIComponent(val); } catch (e) { return val; }
            }

            // Find all clickable terms in the content
            document.querySelectorAll('.clickable-term').forEach(function(element) {
                const term = element.getAttribute('data-term');
                const imagePath = element.getAttribute('data-image');
                const description = safeDecodeAttr(element.getAttribute('data-description'));

                if (term && description) {
                    textDefinitions[term] = {
                        content: element.textContent,
                        description: description,
                        image: imagePath || null
                    };
                }
            });

            // Get modal elements
            const modal = document.getElementById('textDefinitionModal');
            const modalContent = document.getElementById('textModalContent');
            const modalTitle = document.getElementById('textModalTitle');
            const modalImage = document.getElementById('textModalImage');
            const modalImageContainer = document.getElementById('textModalImageContainer');
            const modalBackdrop = modal.querySelector('.text-modal-backdrop');
            const modalClose = document.getElementById('textModalCloseBtn');

            if (!modal || !modalContent || !modalTitle) {
                console.warn('Modal elements not found');
                return;
            }

            // Handle clicks on clickable text
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('clickable-term')) {
                    e.preventDefault();
                    const term = e.target.getAttribute('data-term');

                    // Try different variations of the term key
                    let definition = textDefinitions[term] ||
                        textDefinitions[term.toLowerCase()] ||
                        textDefinitions[term.toLowerCase().replace(/\s+/g, '-')] ||
                        textDefinitions[term.toLowerCase().replace(/\s+/g, '_')];

                    if (definition) {
                        showTextModal(term, definition);
                    } else {
                        // Fallback: show modal with term not found
                        showTextModal(term, {
                            description: 'لم يتم العثور على تعريف مفصل لهذا المصطلح.',
                            image: null
                        });
                    }
                }
            });

            // Function to show modal with text definition (including image)
            function showTextModal(term, definition) {
                modalTitle.textContent = term;

                // Apply quote replacement to the description
                const descriptionWithQuotes = definition.description.replace(/"([^"]*)"/g, '«$1»');
                modalContent.innerHTML = descriptionWithQuotes;

                if (definition.image) {
                    modalImageContainer.style.display = 'block';
                    modalImage.src = definition.image;
                    modalImage.alt = definition.content || 'صورة التعريف';
                } else {
                    modalImageContainer.style.display = 'none';
                    modalImage.onclick = null;
                }

                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            // Function to close modal
            function closeTextModal() {
                const modalContainer = modal.querySelector('.text-modal-container');

                // Add closing animation classes
                modal.classList.add('closing');
                modalContainer.classList.add('closing');

                // Wait for animation to complete before hiding
                setTimeout(function() {
                    modal.style.display = 'none';
                    modal.classList.remove('closing');
                    modalContainer.classList.remove('closing');
                    document.body.style.overflow = '';
                }, 300); // Match animation duration
            }

            // Event listeners for closing modal
            modalBackdrop.addEventListener('click', closeTextModal);
            modalClose.addEventListener('click', closeTextModal);

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeTextModal();
                }
            });

            // Prevent modal container click from closing modal
            modal.querySelector('.text-modal-container').addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeTextDefinitionModal();
            replaceQuotesInPage();
        });

        /**
         * Replace all straight quotes "" with guillemets «» throughout the page
         */
        function replaceQuotesInPage() {
            // Function to recursively replace quotes in text nodes
            function replaceInNode(node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    // Replace all occurrences of "text" with «text»
                    node.textContent = node.textContent.replace(/"([^"]*)"/g, '«$1»');
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    // Skip script and style tags
                    if (node.tagName !== 'SCRIPT' && node.tagName !== 'STYLE') {
                        for (let child of node.childNodes) {
                            replaceInNode(child);
                        }
                    }
                }
            }

            // Process the entire body to replace all quotes
            replaceInNode(document.body);
        }

        // Expand icon on the MAIN image only; body images stay plainly clickable
        function initContentImageExpand() {
            const modal = document.getElementById('imagePreviewModal');
            const previewImg = document.getElementById('previewImg');
            const previewTitle = document.getElementById('previewTitle');
            if (!modal || !previewImg) return;

            function openModal(img) {
                const fig = img.closest('figure');
                const caption = fig ? (fig.querySelector('figcaption')?.textContent?.trim() || '') : '';
                previewImg.src = img.src;
                if (previewTitle) previewTitle.textContent = caption;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            // Body/content images: clickable, no icon
            document.querySelectorAll('.intro img').forEach(img => {
                if (img.closest('.read-more-block')) return;
                img.style.cursor = 'pointer';
                img.addEventListener('click', function() {
                    openModal(img);
                });
            });
        }

        // Image preview functionality
        function initImagePreview() {
            const previewImages = document.querySelectorAll('.preview-image');
            const modal = document.getElementById('imagePreviewModal');
            const previewImg = document.getElementById('previewImg');
            const previewTitle = document.getElementById('previewTitle');
            const closeBtn = document.getElementById('closeImagePreview');
            const backdrop = document.getElementById('previewBackdrop');

            previewImages.forEach(img => {
                img.addEventListener('click', function(e) {
                    e.preventDefault();
                    const src = this.getAttribute('data-preview-src');
                    const title = this.getAttribute('data-preview-title');
                    
                    previewImg.src = src;
                    previewTitle.textContent = title;
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });
            });

            function closePreview() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Close only via the X button
            closeBtn.addEventListener('click', closePreview);
        }
    </script>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="image-preview-modal" style="display: none;">
        <div id="previewBackdrop" class="preview-backdrop"></div>
        <div class="preview-container">
            <button id="closeImagePreview" class="preview-close-btn" aria-label="إغلاق">×</button>
            <img loading="lazy" decoding="async" id="previewImg" src="" alt="معاينة الصورة" class="preview-image-large">
            <div class="preview-title" id="previewTitle"></div>
        </div>
    </div>

    <style>
        .image-preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .preview-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(5px);
        }

        .preview-container {
            position: relative;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            max-width: 90vw;
            max-height: 90vh;
            animation: previewFadeIn 0.3s ease;
        }

        @keyframes previewFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .preview-image-large {
            max-width: 100%;
            max-height: 80vh;
            object-fit: fill;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .preview-close-btn {
            position: absolute;
            top: -40px;
            right: 0;
            background: none;
            border: none;
            color: #fff;
            font-size: 32px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10001;
        }


        .preview-title {
            color: #fff;
            font-size: 16px;
            font-family: asswat-regular;
            text-align: center;
            max-width: 90vw;
            direction: rtl;
        }

        @media (max-width: 768px) {
            .preview-close-btn {
                top: 10px;
            }

            .preview-image-large {
                max-height: 60vh;
            }

            .preview-title {
                font-size: 14px;
                padding: 0 20px;
            }
        }

        @media (max-width: 768px) {
            .economy-grid-container-news {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .economy-grid-container-news {
                grid-template-columns: 1fr;
            }
        }
    </style>

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

    <style>
        /* Make header logo + menu button span full page width */
        #main-header > .container,
        #scroll-navbar > .container {
            max-width: 100%;
            padding: 16px 45px;
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
    </style>

    <script>
        (function() {
            const panel = document.getElementById('themeMenuPanel');
            const overlay = document.getElementById('themeMenuOverlay');
            const closeBtn = document.getElementById('themeMenuClose');
            const openBtns = document.querySelectorAll('[data-theme-menu-open]');
            if (!panel || !overlay) return;

            function setIcons(name) {
                openBtns.forEach(b => {
                    const icon = b.querySelector('.material-symbols-outlined');
                    if (icon) icon.textContent = name;
                });
            }

            const heroSearches = document.querySelectorAll('.theme-hero-search');

            function openMenu() {
                panel.classList.add('open');
                overlay.classList.add('open');
                openBtns.forEach(b => {
                    b.classList.add('is-open');
                    b.setAttribute('aria-expanded', 'true');
                });
                setIcons('close');
                document.documentElement.classList.add('tm-no-scroll');
                panel.setAttribute('aria-hidden', 'false');
                heroSearches.forEach(s => {
                    s.classList.add('hide-search');
                    const i = s.querySelector('.theme-hero-search-input');
                    if (i) i.classList.remove('active');
                });
            }

            function closeMenu() {
                panel.classList.remove('open');
                overlay.classList.remove('open');
                openBtns.forEach(b => {
                    b.classList.remove('is-open');
                    b.setAttribute('aria-expanded', 'false');
                });
                setIcons('dehaze');
                document.documentElement.classList.remove('tm-no-scroll');
                panel.setAttribute('aria-hidden', 'true');
                heroSearches.forEach(s => s.classList.remove('hide-search'));
            }

            openBtns.forEach(b => b.addEventListener('click', function() {
                panel.classList.contains('open') ? closeMenu() : openMenu();
            }));
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
    </script>
</body>

</html>
