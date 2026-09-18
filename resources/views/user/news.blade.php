@extends('layouts.index')

@section('title', $news->title)

@if ($news->status !== 'published')
    @section('meta_robots', 'noindex, nofollow')
@endif

@php
    $shareTitle = $news->share_title ?: $news->long_title;
    $shareDescription = $news->share_description ?: $news->summary;

    // Build article-specific keywords from seo_keyword and tag names (for meta keywords)
    $tagNames = $news->tags->pluck('name')->filter()->all();
    $articleKeywordsParts = [];
    if (!empty($news->seo_keyword)) {
        $articleKeywordsParts[] = $news->seo_keyword;
    }
    if (!empty($tagNames)) {
        $articleKeywordsParts[] = implode(', ', $tagNames);
    }
    $articleKeywords = $articleKeywordsParts ? implode(', ', $articleKeywordsParts) : null;

    // Build a clean, absolute URL for Open Graph / Twitter images
    if (!empty($news->share_image)) {
        // If share_image is already a full URL, use it; otherwise convert it to an asset URL
        if (filter_var($news->share_image, FILTER_VALIDATE_URL)) {
            $shareImageUrl = $news->share_image;
        } else {
            $shareImageUrl = asset(ltrim($news->share_image, '/'));
        }
    } else {
        // Fallback to main media relation, using the accessor that returns a full URL
        $mainMedia = $news->media()->wherePivot('type', 'main')->first();
        $shareImageUrl = $mainMedia?->url;
    }

    // Final fallback to a default image in case nothing else is available
    if (empty($shareImageUrl)) {
        $shareImageUrl = asset('covergoogle.png');
    }
@endphp

@section('meta_description', $shareDescription)
@section('meta_keywords', $articleKeywords)
@section('meta_og_title', $shareTitle)
@section('meta_og_description', $shareDescription)
@section('meta_og_image', $shareImageUrl)
@section('meta_twitter_title', $shareTitle)
@section('meta_twitter_description', $shareDescription)
@section('meta_twitter_image', $shareImageUrl)

@push('seo')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=chevron_left,chevron_right,close,expand_content" />
    @php
        $authorNames = $news->writers->pluck('name')->filter()->values();
        $sectionName = $news->section?->name;
        $publishedIso = optional($news->published_date)->toIso8601String();
        $modifiedIso = optional($news->updated_at)->toIso8601String();
        $articleUrl = route('news.show', $news->shortlink);
        $logoUrl = asset('covergoogle.png');

        $articleSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $articleUrl,
            ],
            'headline' => mb_substr($news->title ?? '', 0, 110),
            'description' => $shareDescription,
            'image' => [$shareImageUrl],
            'datePublished' => $publishedIso,
            // dateModified intentionally omitted so Google shows the published date
            'author' => $authorNames->isEmpty()
                ? [['@type' => 'Organization', 'name' => 'أصوات جزائرية']]
                : $authorNames->map(fn($n) => ['@type' => 'Person', 'name' => $n])->all(),
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => 'أصوات جزائرية',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logoUrl,
                ],
            ],
            'articleSection' => $sectionName,
            'inLanguage' => 'ar',
            'keywords' => $articleKeywords,
        ], fn($v) => !empty($v) || $v === 0);
    @endphp

    @if ($publishedIso)
        <meta property="article:published_time" content="{{ $publishedIso }}">
    @endif
    @if ($sectionName)
        <meta property="article:section" content="{{ $sectionName }}">
    @endif
    @foreach ($authorNames as $authorName)
        <meta property="article:author" content="{{ $authorName }}">
    @endforeach
    @foreach ($tagNames as $tag)
        <meta property="article:tag" content="{{ $tag }}">
    @endforeach

    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')

    <script>
        // Function to process social embeds (Instagram, Facebook, X/Twitter, etc.)
        function processEmbeds() {
            console.log('Processing embeds...');

            // Process Instagram embeds
            if (window.instgrm && window.instgrm.Embed) {
                console.log('Found Instagram, processing...');
                try {
                    window.instgrm.Embed.process();
                } catch (e) {
                    console.error('Error processing Instagram:', e);
                }
            }

            // Process Facebook embeds (posts, reels, videos)
            if (window.FB && window.FB.XFBML && typeof window.FB.XFBML.parse === 'function') {
                console.log('Found Facebook, processing...');
                try {
                    // Parse the whole document so dynamically-inserted embeds are resized
                    window.FB.XFBML.parse();
                } catch (e) {
                    console.error('Error processing Facebook:', e);
                }
            }

            // Process X (Twitter) embeds
            if (window.twttr && window.twttr.widgets) {
                console.log('Found Twitter/X, processing...');
                try {
                    window.twttr.widgets.load();
                } catch (e) {
                    console.error('Error processing Twitter/X:', e);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.custom-article-content a').forEach(function(el) {
                el.setAttribute('target', '_blank');
                el.setAttribute('rel', 'noopener');
            });

            // Read more card click handler - for dynamically inserted content from TinyMCE
            document.addEventListener('click', function(e) {
                const card = e.target.closest('.read-more-block');
                if (card) {
                    const shortlink = card.dataset.shortlink;
                    if (shortlink) {
                        e.preventDefault();
                        window.open('/article/' + shortlink, '_blank', 'noopener');
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
                            window.open('/article/' + shortlink, '_blank', 'noopener');
                        }
                    }
                }
            });

            // Run the SDK processor (Facebook, Instagram, X) at intervals.
            processEmbeds();
            setTimeout(processEmbeds, 500);
            setTimeout(processEmbeds, 1500);
            setTimeout(processEmbeds, 3000);
            setTimeout(processEmbeds, 5000);

            // Helper: replace a broken Facebook embed with a styled click-through card.
            function renderFbFallbackCard(el) {
                if (el.dataset.fbHandled === '1') return;

                var url = el.getAttribute('data-fb-url');
                if (!url) {
                    var anchor = el.querySelector('a[href*="facebook.com"]');
                    if (anchor) url = anchor.getAttribute('href');
                }
                if (!url) return;

                try {
                    var u = new URL(url);
                    if (/(^|\.)facebook\.com$/i.test(u.hostname)) u.hostname = 'www.facebook.com';
                    ['__cft__[0]', '__cft__', '__tn__', 'mibextid', '__xts__[0]', '_rdc', '_rdr'].forEach(function(k) { u.searchParams.delete(k); });
                    u.hash = '';
                    url = u.toString();
                } catch (e) {}

                var pageName = '';
                try {
                    var seg = new URL(url).pathname.split('/').filter(Boolean);
                    if (seg.length) pageName = decodeURIComponent(seg[0]).replace(/[._-]/g, ' ');
                } catch (e) {}

                el.querySelectorAll('.fb-post, .fb-video, .fb_iframe_widget, blockquote, .fb-embed-url, .fb-embed-title, iframe').forEach(function(n) { n.remove(); });

                var card = document.createElement('a');
                card.href = url;
                card.target = '_blank';
                card.rel = 'noopener noreferrer';
                card.className = 'fb-card-link';
                card.style.cssText = 'display:flex;align-items:center;gap:14px;padding:16px 18px;margin:16px auto;max-width:560px;background:#f0f2f5;border:1px solid #dadde1;border-radius:10px;text-decoration:none;color:#050505;transition:background .15s;';
                card.onmouseover = function() { card.style.background = '#e4e6eb'; };
                card.onmouseout  = function() { card.style.background = '#f0f2f5'; };
                card.innerHTML =
                    '<div style="flex:0 0 48px;width:48px;height:48px;border-radius:50%;background:#1877f2;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:26px;font-family:Arial,sans-serif;">f</div>' +
                    '<div style="flex:1;min-width:0;text-align:right;">' +
                        '<div style="font-family:asswat-medium;font-size:15px;color:#1877f2;margin-bottom:2px;">منشور على فيسبوك</div>' +
                        (pageName ? '<div style="font-family:asswat-medium;font-size:14px;color:#050505;margin-bottom:4px;">' + pageName + '</div>' : '') +
                        '<div style="font-size:12px;color:#65676b;direction:ltr;text-align:left;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + url + '</div>' +
                    '</div>' +
                    '<div style="flex:0 0 auto;color:#1877f2;font-size:18px;">↗</div>';
                el.appendChild(card);
                el.dataset.fbHandled = '1';
            }

            // After the SDK has had time to render, check each Facebook embed.
            // If the SDK did NOT produce an iframe, OR the rendered iframe is
            // too short to contain real post content (Facebook's "no longer
            // available" message is much smaller than a real post), swap in
            // the styled fallback card.
            setTimeout(function() {
                document.querySelectorAll('.fb-embed-block').forEach(function(el) {
                    var iframe = el.querySelector('iframe');
                    if (!iframe) {
                        renderFbFallbackCard(el);
                        return;
                    }
                    // Facebook's "no longer available" placeholder is very short
                    // (~150–220px). Real posts — even text-only ones — are
                    // taller. Only treat very small iframes as broken.
                    if (iframe.offsetHeight > 0 && iframe.offsetHeight < 230) {
                        renderFbFallbackCard(el);
                    }
                });

                document.querySelectorAll('.x-embed-block').forEach(function(el) {
                    if (!el.querySelector('iframe')) el.classList.add('embed-fallback');
                });
                document.querySelectorAll('.ig-embed-block').forEach(function(el) {
                    if (!el.querySelector('iframe')) el.classList.add('embed-fallback');
                });
            }, 7000);
        });

        // Expose globally for manual triggering if needed
        window.processEmbeds = processEmbeds;
    </script>

    {{-- ================= CSS ================= --}}
    <style>
        /* Critical visibility CSS to avoid flash of wrong layout on first paint */
        .web {
            display: none;
        }

        .mobile {
            display: block;
        }



        @media (min-width: 992px) {
            .web {
                display: block !important;
            }

            .mobile {
                display: none !important;
            }
        }

        /* Layout */
        .web {
            width: 100%;
        }

        .custom-article-content a {
            color: #000000;
            text-decoration: none;
            position: relative;
            border-bottom: 2px solid #e4f0ef;
            border-radius: 0;
            padding-bottom: 1px;
            display: inline;
            transition: color 0.3s ease;
        }

        .custom-article-content a::before {
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

        .custom-article-content a:hover::before {
            height: calc(100% + 2px);
        }

        .custom-container {
            max-width: 1300px;
            margin: 40px auto;
            display: flex;
            flex-direction: row;
            gap: 20px;
            padding: 0 11px;
        }

        .custom-main {
            flex: 0 0 70%;
            max-width: 70%;
        }

        .custom-sidebar {
            flex: 0 0 calc(30% - 20px);
            max-width: calc(30% - 20px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .web {
                display: none;
            }
        }

        @media (min-width: 768px) {
            .mobile {
                display: none;
            }

            .web {
                display: block !important;
            }
        }

        @media (max-width: 900px) {
            .custom-container {
                flex-direction: column;
                gap: 0;
            }

            .custom-main,
            .custom-sidebar {
                width: 100%;
                flex: unset;
            }

            .custom-sidebar {
                margin-top: 30px;
            }
        }

        /* Article meta */
        .custom-category {
            font-size: 16px;
            font-family: asswat-medium;
            color: #888;
            margin-bottom: 10px;
            text-align: right;
        }

        .custom-article-title {
            font-size: 40px;
            font-family: asswat-medium;
            color: #141414;
            line-height: 1.4;
            text-align: right;
            margin-bottom: 20px;
        }

        .custom-article-summary {
            font-size: 18px;
            color: #555;
            font-family: asswat-regular;
            font-weight: bolder;
            margin-bottom: 15px;
            text-align: right;
        }

        .custom-meta,
        .custom-meta-date {
            font-size: 15px;
            color: #141414;
            font-family: asswat-light;
            text-align: right;
        }

        /* Images */
        .custom-article-image-wrapper {
            width: 100%;
            overflow: hidden;
            margin-bottom: 20px;
            position: relative;
        }

        .custom-article-image-wrapper img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        /* Image caption styling */
        .custom-article-image-wrapper figcaption {
            background: #F5F5F5;
            color: #555;
            font-size: 15px;
            padding: 10px;
            text-align: right;
            font-family: asswat-regular;
            direction: rtl;
        }

        .custom-article-image-wrapper figcaption:empty {
            display: none;
        }

        /* ===================== CONTENT ===================== */
        .custom-article-content {
            font-size: 16px !important;
            font-family: asswat-regular;
            color: #000000;
            line-height: 1.625rem;
            text-align: right;
            margin-bottom: 30px;
        }

        /* Desktop reading style. Scoped to >=769px so the mobile article keeps
           the size and ragged edge it had; this template has no other mobile
           override for the body copy. */
        @media (min-width: 769px) {
            .custom-article-content {
                font-size: 18px !important;
                line-height: 31px;
                text-align: justify;
            }
        }

        .custom-article-content p span {
            font-size: 28px !important;
            font-family: asswat-regular;
            color: #333;
            line-height: 1.9;
            text-align: right;
            margin: 5px 0px;
        }

        .custom-article-content * {
            font-family: asswat-regular !important;
            direction: rtl !important;
            box-sizing: border-box;
        }

        .custom-article-content h2,
        .custom-article-content h4 {
            font-family: asswat-medium !important;
            color: #111 !important;
            text-align: right !important;
            margin-top: 35px !important;
            margin-bottom: 35px !important;
            font-size: 32px !important;
        }

        .custom-article-content h3,
        .custom-article-content h3 * {
            color: #000 !important;
            font-size: 16px !important;
            font-family: asswat-bold !important;
            font-weight: normal !important;
            line-height: 1.9 !important;
            text-align: right !important;
        }

        .custom-article-content h3 {
            margin: 24px 0 !important;
        }

        /* Image spacing and captions */
        .custom-article-content img {
            display: block;
            max-width: 100% !important;
            height: auto !important;
        }

        /* Horizontal rule inserted via TinyMCE */
        .custom-article-content hr {
            border: none;
            height: 1px;
            background-color: #cacaca;
            margin: 24px 0;
        }
        /* Article text/content wrapper: 88% on desktop (so reading column matches design),
           full width on mobile (avoids overly narrow column + horizontal overflow). */
        .article-text-wrapper { width: 100%; margin: 0 auto; }
        @media (min-width: 992px) {
            .article-text-wrapper { width: 88%; }
        }

        /* Break-out: content images/figures inside the 78% text wrapper extend to
           match the feature-image width. Galleries are intentionally excluded so they
           stay at the text-column width. Desktop only — on mobile the wrapper is 100%
           and the break-out would overflow the screen. .tiny-sm and slider/lightbox
           images are excluded so they keep their intended sizes. */
        @media (min-width: 992px) {
            .custom-article-content > figure:not(.audio),
            .custom-article-content > p > img:not(.tiny-sm):not(.vvc-cgs-img):not(.vvc-cglb-img),
            .custom-article-content > img:not(.tiny-sm):not(.vvc-cgs-img):not(.vvc-cglb-img) {
                width: 113.64% !important;
                max-width: 113.64% !important;
                margin-left: -6.82% !important;
                margin-right: -6.82% !important;
            }
            /* Inside galleries: each image fills its (now wider) container. */
            .custom-article-content .vvc-cgallery-grid img,
            .custom-article-content .vvc-cgallery-masonry img,
            .custom-article-content .vvc-cgallery img {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100% !important;
            }
            /* Inside <figure>: the inner <img> fills the figure (no double break-out). */
            .custom-article-content > figure > img {
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        /* Content figure styling */
        .custom-article-content figure {
            width: 100%;
            margin: 25px 0;
        }

        .custom-article-content figure img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .custom-article-content figcaption {
            background: #F5F5F5;
            color: #555;
            font-size: 15px;
            padding: 10px;
            text-align: right;
            font-family: asswat-regular;
            direction: rtl;
        }

        .custom-article-content figcaption:empty {
            display: none;
        }

        /* Video styling within content */
        .custom-article-content video {
            width: 100%;
            height: auto;
        }

        .custom-article-content iframe[src*="youtube"],
        .custom-article-content iframe[src*="vimeo"],
        .custom-article-content iframe[src*="dailymotion"] {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            border: none;
        }

        /* Audio styling within content */
        /* Native <audio> is kept only as the playback engine; the custom player UI
           below replaces its controls. */
        .custom-article-content audio,
        .mobile-article-content audio {
            display: none !important;
        }

        .custom-article-content figure.audio,
        .mobile-article-content figure.audio {
            margin: 25px 0;
            width: 100%;
        }

        /* ===== Custom audio player ===== */
        .custom-article-content .aud-player,
        .mobile-article-content .aud-player {
            direction: ltr !important;
            flex-direction: row !important;
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            box-sizing: border-box;
            background: #f5f5f5;
            border-radius: 0;
            padding: 12px 16px;
            margin: 25px 0;
            font-family: asswat-medium;
        }

        .custom-article-content figure.audio > .aud-player,
        .mobile-article-content figure.audio > .aud-player {
            margin: 0;
        }

        .aud-player .aud-play {
            flex: 0 0 44px;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: #444;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: background .2s ease, transform .1s ease;
        }

        .aud-player .aud-play:hover {
            background: #333;
        }

        .aud-player .aud-play:active {
            transform: scale(.94);
        }

        .aud-player .aud-play svg {
            display: block;
        }

        .aud-player .aud-bar {
            position: relative;
            flex: 1 1 auto;
            height: 6px;
            background: #d9d9d9;
            border-radius: 3px;
            cursor: pointer;
        }

        .aud-player .aud-fill {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 0;
            background: #444;
            border-radius: 3px;
        }

        .aud-player .aud-knob {
            position: absolute;
            top: 50%;
            left: 0;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #444;
            transform: translate(-50%, -50%);
            transition: opacity .2s ease;
        }

        .aud-player .aud-time {
            flex: 0 0 auto;
            min-width: 40px;
            text-align: center;
            font-size: 13px;
            color: #555;
            font-variant-numeric: tabular-nums;
        }

        /* ===== Content tables ===== */
        .custom-article-content .table-wrap {
            width: 100%;
            overflow-x: auto;
            margin: 25px 0;
            -webkit-overflow-scrolling: touch;
            border: 1px solid #ececec;
            border-radius: 0;
        }

        .custom-article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            direction: rtl;
            font-size: 15px;
            color: #222;
            background: #f1f1f1;
            min-width: 480px;
        }

        .custom-article-content .table-wrap > table {
            border: none;
        }

        .custom-article-content table th,
        .custom-article-content table td {
            border: 1px solid #ececec;
            padding: 12px 14px;
            text-align: right;
            vertical-align: middle;
            line-height: 1.65;
        }

        .custom-article-content table th,
        .custom-article-content table thead td {
            background: #f1f1f1;
            color: #111;
            font-family: asswat-bold !important;
            font-weight: normal;
        }

        .custom-article-content table td {
            font-family: asswat-medium !important;
            background: #f1f1f1;
        }

        .custom-article-content table td.td-active,
        .custom-article-content table th.td-active {
            background: #ddd;
        }

        /* Headings inside a table cell must not add their outside vertical spacing */
        .custom-article-content table th h1,
        .custom-article-content table th h2,
        .custom-article-content table th h3,
        .custom-article-content table th h4,
        .custom-article-content table th h5,
        .custom-article-content table th h6,
        .custom-article-content table td h1,
        .custom-article-content table td h2,
        .custom-article-content table td h3,
        .custom-article-content table td h4,
        .custom-article-content table td h5,
        .custom-article-content table td h6 {
            margin: 0 !important;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Blockquote */
        .custom-article-content blockquote {
            width: 100%;
            padding: 40px 20px;
            margin: 30px 0;
            text-align: center;
            position: relative;
            font-family: asswat-medium;
        }

        .custom-article-content blockquote p span {
            font-size: 28px;
            color: #222;
            line-height: 1.6;
            font-family: asswat-bold !important;
            text-align: center !important;
        }

        .custom-article-content blockquote p {
            font-size: 28px;
            color: #222;
            line-height: 1.6;
            font-family: asswat-bold !important;
            text-align: center !important;
        }

        .custom-article-content blockquote::before {
            content: "";
            position: absolute;
            top: 0px;
            right: 0px;
            width: 32px;
            height: 32px;
            background: url('/user/assets/icons/up.png') no-repeat center;
            background-size: contain;
        }

        .custom-article-content blockquote::after {
            content: "";
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 32px;
            height: 32px;
            background: url('/user/assets/icons/down.png') no-repeat center;
            background-size: contain;
        }

        /* Prevent blockquote decorative icons on twitter-tweet embeds */
        .custom-article-content blockquote.twitter-tweet::before,
        .custom-article-content blockquote.twitter-tweet::after {
            display: none !important;
        }

        /* Reset blockquote styling for twitter-tweet embeds */
        .custom-article-content blockquote.twitter-tweet {
            padding: 0 !important;
            margin: 20px auto !important;
            text-align: initial !important;
            font-family: inherit !important;
            position: relative;
        }

        .custom-article-content blockquote.twitter-tweet p {
            font-size: inherit !important;
            font-family: inherit !important;
            text-align: initial !important;
        }

        /* Instagram Embed Styles */
        .custom-article-content .instagram-media {
            margin: 20px auto !important;
            width: 100% !important;
            max-width: 540px !important;
        }

        .custom-article-content .instagram-media iframe {
            max-width: 100% !important;
        }

        /* Facebook Embed Styles (posts, reels, videos) */
        .custom-article-content .fb-post,
        .custom-article-content .fb-video,
        .custom-article-content .fb_iframe_widget,
        .custom-article-content .fb_iframe_widget span {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            line-height: 0 !important; /* avoid extra line-height gap under iframe */
            margin: 0 auto 20px auto !important; /* controlled spacing under the reel */
            overflow: hidden !important; /* hide any excess height Facebook reserves */
        }

        .custom-article-content .fb_iframe_widget iframe,
        .custom-article-content iframe[src*="facebook.com"] {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            height: 100% !important; /* fill the reserved height without extra blank */
        }

        /* X (Twitter) Embed Styles */
        .custom-article-content .twitter-tweet {
            margin: 20px auto !important;
            max-width: 550px !important;
        }

        .custom-article-content .twitter-tweet iframe {
            max-width: 100% !important;
        }

        /* Tags */
        .custom-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            row-gap: 22px;
            margin-bottom: 40px;
        }

        .custom-tags span {
            background: #f1f1f1;
            color: #000;
            font-size: 14px;
            padding: 6px 14px;
            font-family: asswat-medium;
            cursor: pointer;
            transition: 0.2s;
        }

        .custom-tags span:hover {
            background: #ddd;
        }

        /* Writer Card */
        .writer-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #fafafa;
            border: 1px solid #eee;
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

        /* Floating podcast */
        .floating-podcast-player {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: #fff;
            border-radius: 12px;
            padding: 16px 24px;
            display: none;
            align-items: center;
            gap: 16px;
            width: 80%;
            max-width: 800px;
            border: 1px solid #ddd;
        }

        .floating-podcast-player .close-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: #888;
            cursor: pointer;
            margin-right: 8px;
        }

        .news-card-horizontal-news {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            direction: rtl;
            margin-bottom: 10px;
        }

        .news-card-horizontal-news .news-card-image-news img {
            width: 140px;
            aspect-ratio: 16/9;
            object-fit: cover;
            display: block;
        }

        .news-card-horizontal-news .news-card-text-news {
            flex: 1;
        }

        .news-card-horizontal-news .news-card-text-news h3 {
            font-size: 12px;
            margin: 0 0 4px;
            color: #74747C;
            font-family: asswat-light;
            font-weight: lighter;
        }

        .news-card-horizontal-news .news-card-text-news p {
            font-size: 14px;
            margin: 0;
            font-family: asswat-medium;
            line-height: 1.4;
        }

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

        .economy-card-news h3 {
            cursor: pointer;
            font-family: asswat-light;
            font-weight: lighter;
        }

        .economy-card-news h2:hover {
            text-decoration: underline;
            cursor: pointer;
        }

        @media (max-width: 600px) {
            .floating-podcast-player {
                width: 95%;
                padding: 10px 12px;
            }
        }

        /* ===== Social Share Section ===== */
        .custom-date-share {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .date-text {
            color: #888;
            font-size: 15px;
            margin: 0;
        }

        .share-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
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
            color: #333;
        }

        .share-icons img {
            width: 30px;
            height: 30px;
        }

        .share-btn {
            background: #ffffff;
            border: none;
            border-radius: 50%;
            width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .share-btn:hover {
            background: #f5f5f5;
        }

        .newCategoryReadMoreNews {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .newCategoryReadMoreNews-list {
            margin-top: 12px;
            display: flex;
            align-items: center;
            direction: rtl;
            font-family: asswat-bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .newCategoryReadMoreNews-list:last-child {
            border-bottom: none;
        }

        .newCategoryReadMoreNews-list .number {
            font-size: 32px;
            color: #e7e7e7;
            margin-left: 10px;
            font-weight: bold;
        }

        .newCategoryReadMoreNews-list p {
            font-size: 16px;
            color: #333;
            line-height: 1.4;
        }

        .section-title {
            font-size: 20px;
            font-family: asswat-bold;
            color: #141414;
            text-align: right;
        }

        /* Clickable text styling */
        .custom-article-content p .clickable-term {
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
            padding: 3px;
            box-sizing: border-box;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        /* Text definition modal */
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

        @keyframes closeBtnPulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
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
            opacity: 0.9;
        }

        /* Full-screen image viewer */
        .feature-image-clickable {
            cursor: pointer;
        }

        .fullscreen-image-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: none;
            z-index: 10001;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fullscreen-image-modal.active {
            display: flex;
        }

        .fullscreen-image-container {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fullscreen-image-container img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            display: block;
        }

        .fullscreen-image-close {
            position: fixed;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
            z-index: 10002;
        }

        .fullscreen-image-close:hover {
            background: rgba(0, 0, 0, 0.45);
        }

        .fullscreen-image-close .material-symbols-outlined {
            font-size: 22px;
        }


        .fullscreen-image-prev,
        .fullscreen-image-next {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.45);
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
            z-index: 10003;
        }

        .fullscreen-image-prev:hover,
        .fullscreen-image-next:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .fullscreen-image-prev .material-symbols-outlined,
        .fullscreen-image-next .material-symbols-outlined {
            font-size: 28px;
        }

        .fullscreen-image-prev {
            right: 20px;
        }

        .fullscreen-image-next {
            left: 20px;
        }



        .fullscreen-image-prev:disabled,
        .fullscreen-image-next:disabled {
            opacity: 0.1;
            cursor: not-allowed;
            pointer-events: none;
        }

        .fullscreen-image-counter {
            position: fixed;
            top: 20px;
            left: 20px;
            background: none;
            color: #fff;
            padding: 10px 20px;
            font-family: asswat-medium;
            font-size: 16px;
            z-index: 10003;
        }

        .fullscreen-image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 15px 25px;
            text-align: right;
            font-family: asswat-regular;
            direction: rtl;
        }

        /* Make content images clickable */
        .custom-article-content img {
            cursor: pointer;
            transition: opacity 0.3s;
        }

        /* Expand icon on content images */
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
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .content-img-wrap {
            position: relative;
            display: block;
            width: fit-content;
            max-width: 100%;
        }

        .content-img-expand {
            position: absolute;
            bottom: 12px;
            left: 12px;
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

        @media (max-width: 768px) {
            .text-modal-container {
                width: 100%;
                max-height: 90vh;
                bottom: 0;
            }

            .text-modal-body {
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
                max-width: 100%;
            }

            #textModalImageContainer {
                order: -1;
                justify-content: center;
            }

            #textModalImage {
                width: auto;
                height: auto;
                max-width: 100%;
                max-height: 300px;
                cursor: pointer;
            }

            .text-modal-body p {
                padding: 0;
                font-size: 16px;
            }

            .text-modal-title {
                font-size: 22px;
                margin-bottom: 12px;
            }

            .economy-grid-container-news {
                grid-template-columns: repeat(2, 1fr);
            }

            .fullscreen-image-prev,
            .fullscreen-image-next {
                font-size: 40px;
                width: 50px;
                height: 60px;
                padding: 10px 8px;
            }

            .fullscreen-image-prev {
                right: 10px;
            }

            .fullscreen-image-next {
                left: 10px;
            }

            .fullscreen-image-counter {
                font-size: 14px;
                padding: 8px 15px;
                top: 15px;
                left: 15px;
            }

            .fullscreen-image-close {
                font-size: 32px;
                width: 40px;
                height: 40px;
                top: 15px;
                right: 15px;
            }
        }

        @media (max-width: 480px) {
            .economy-grid-container-news {
                grid-template-columns: 1fr;
            }

            .custom-article-title {
                font-size: 28px;
            }
        }

        /* ===== Read More Card ===== */
        .read-more-block {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 12px;
            direction: rtl;
            margin-top: 28px;
            margin-bottom: 28px;
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

        /* Placeholder state (before content loads) */
        .read-more-block .read-more-placeholder {
            display: block;
            text-align: center;
            color: #999;
            font-size: 14px;
            padding: 1rem;
        }

        /* Hide placeholder when content is loaded */
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

        .read-more-label {
            position: absolute;
            right: 0;
            top: 0;
            background: #e7e7e7;
            color: #333;
            font-family: asswat-bold;
            font-size: 13px;
            padding: 3px 12px 3px 8px;
            border-bottom-left-radius: 12px;
            border-top-right-radius: 4px;
            z-index: 2;
            letter-spacing: 0.5px;
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

        @media (max-width: 600px) {
            .read-more-block {
                flex-direction: row;
                gap: 8px;
                background: #f9f9f9;
                border-radius: 0;
                overflow: visible;
                border: none;
                box-shadow: none;
                transition: none;
                align-items: center;
            }

            .read-more-block:active {
                box-shadow: none;
                transform: none;
            }

            .read-more-label-text {
                font-size: 13px;
                padding: 0;
                margin: 0;
                display: block;
                order: -1;
                min-width: 60px;
                text-align: center;
            }

            .read-more-image {
                width: 120px;
                height: 70px;
                order: 0;
                flex-shrink: 0;
            }

            .read-more-content {
                padding: 0;
                gap: 4px;
                flex: 1;
            }

            .read-more-label {
                display: none;
            }

            .read-more-category {
                font-size: 11px;
                color: #999;
                margin: 0 !important;
            }

            .read-more-title {
                font-size: 14px;
                line-height: 1.3;
                margin: 0 !important;
            }

            .read-more-summary {
                font-size: 13px;
                color: #666;
                line-height: 1.3;
                display: none;
            }
        }

        /* ===== IMPROVED AUDIO PLAYER STYLES ===== */
        .audio-player-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        .audio-player-image {
            position: relative;
            width: 100%;
            overflow: hidden;
            cursor: pointer;
        }

        .audio-player-image img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
            aspect-ratio: 16/9;
            transition: filter 0.3s ease;
        }

        .audio-player-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .audio-player-wrapper.playing .audio-player-overlay {
            opacity: 1;
            background: rgba(0, 0, 0, 0.6);
        }

        .audio-player-wrapper.playing .audio-player-image img {
            filter: brightness(0.7);
        }

        .audio-play-icon {
            position: absolute;
            bottom: 20px;
            left: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            transition: all 0.3s ease;
        }



        .audio-play-icon i {
            font-size: 23px;
            color: #ffffff;
            margin-left: 2px;
        }


        .audio-player-wrapper.playing .audio-play-icon i {
            color: #ffffff;
        }

        /* Updated Creative podcast lines - Aligned with play icon - Interactive for seeking */
        .podcast-lines {
            position: absolute;
            bottom: 20px;
            left: 65px;
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 4px;
            height: 35px;
            cursor: pointer;
        }

        .audio-player-wrapper.playing .podcast-lines {
            opacity: 1;
            pointer-events: auto;
        }

        .podcast-line {
            width: 3px;
            background: #ffffff89;
            border-radius: 2px;
            animation: wave 1.5s ease-in-out infinite;
            transform-origin: center;
        }

        /* Fade effect for lines - darker to lighter from left to right */
        .podcast-line:nth-child(1) {
            opacity: 1;
        }

        .podcast-line:nth-child(2) {
            opacity: 0.95;
        }

        .podcast-line:nth-child(3) {
            opacity: 0.9;
        }

        .podcast-line:nth-child(4) {
            opacity: 0.85;
        }

        .podcast-line:nth-child(5) {
            opacity: 0.8;
        }

        .podcast-line:nth-child(6) {
            opacity: 0.75;
        }

        .podcast-line:nth-child(7) {
            opacity: 0.7;
        }

        .podcast-line:nth-child(8) {
            opacity: 0.65;
        }

        .podcast-line:nth-child(9) {
            opacity: 0.6;
        }

        .podcast-line:nth-child(10) {
            opacity: 0.55;
        }

        .podcast-line:nth-child(11) {
            opacity: 0.5;
        }

        .podcast-line:nth-child(12) {
            opacity: 0.45;
        }

        .podcast-line:nth-child(13) {
            opacity: 0.4;
        }

        .podcast-line:nth-child(14) {
            opacity: 0.35;
        }

        .podcast-line:nth-child(15) {
            opacity: 0.3;
        }

        /* Different heights and animation delays for each line */
        .podcast-line:nth-child(1) {
            height: 20px;
            animation-delay: 0s;
        }

        .podcast-line:nth-child(2) {
            height: 28px;
            animation-delay: 0.1s;
        }

        .podcast-line:nth-child(3) {
            height: 15px;
            animation-delay: 0.2s;
        }

        .podcast-line:nth-child(4) {
            height: 22px;
            animation-delay: 0.3s;
        }

        .podcast-line:nth-child(5) {
            height: 18px;
            animation-delay: 0.4s;
        }

        .podcast-line:nth-child(6) {
            height: 25px;
            animation-delay: 0.5s;
        }

        .podcast-line:nth-child(7) {
            height: 30px;
            animation-delay: 0.6s;
        }

        .podcast-line:nth-child(8) {
            height: 20px;
            animation-delay: 0.7s;
        }

        .podcast-line:nth-child(9) {
            height: 12px;
            animation-delay: 0.8s;
        }

        .podcast-line:nth-child(10) {
            height: 18px;
            animation-delay: 0.9s;
        }

        .podcast-line:nth-child(11) {
            height: 14px;
            animation-delay: 1.0s;
        }

        .podcast-line:nth-child(12) {
            height: 22px;
            animation-delay: 1.1s;
        }

        .podcast-line:nth-child(13) {
            height: 16px;
            animation-delay: 1.2s;
        }

        .podcast-line:nth-child(14) {
            height: 10px;
            animation-delay: 1.3s;
        }

        .podcast-line:nth-child(15) {
            height: 8px;
            animation-delay: 1.4s;
        }

        @keyframes wave {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.6);
            }
        }

        .audio-player-controls {
            background: transparent;
            padding: 0;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 0;
        }

        .audio-player-controls audio {
            flex: 1;
            height: 40px;
        }

        .audio-time {
            font-family: asswat-regular;
            font-size: 14px;
            color: #666;
            min-width: 100px;
            text-align: center;
        }

        .audio-caption {
            background: #F5F5F5;
            color: #555;
            font-size: 15px;
            padding: 10px;
            text-align: right;
            font-family: asswat-regular;
            direction: rtl;
        }

        /* ===== Glassmorphism podcast control bar (content-player style) ===== */
        .audio-player-wrapper .podcast-lines { display: none !important; }

        .audio-glass-bar {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 6;
            direction: ltr !important;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 0;
            background: transparent;
        }

        .audio-glass-bar .audio-play-icon {
            position: static;
            bottom: auto;
            left: auto;
            flex: 0 0 44px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
        }

        .audio-glass-bar .audio-play-icon i,
        .audio-player-wrapper.playing .audio-glass-bar .audio-play-icon i {
            color: #444;
            font-size: 15px;
            margin-left: 0;
        }

        .audio-glass-bar .audio-progress-container {
            position: relative;
            flex: 1 1 auto;
            height: 3px;
            padding: 0;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 3px;
            cursor: pointer;
        }

        .audio-glass-bar .audio-progress-fill {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 0;
            background: #fff;
            border-radius: 3px;
        }

        .audio-glass-bar .audio-progress-handle {
            position: absolute;
            top: 50%;
            left: 0;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #fff;
            transform: translate(-50%, -50%);
        }

        .audio-glass-bar .audio-time-display {
            position: static;
            bottom: auto;
            right: auto;
            opacity: 1;
            pointer-events: none;
            flex: 0 0 auto;
            color: #fff;
            font-size: 13px;
            font-weight: 400;
        }

        /* Audio time display - positioned on the right side */
        .audio-time-display {
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            z-index: 5;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .audio-player-wrapper.playing .audio-time-display {
            opacity: 1;
        }

        /* Responsive styles for podcast lines and audio controls */
        @media (max-width: 768px) {
            .podcast-lines {
                left: 55px;
                width: 10%;
                height: 30px;
                gap: 3px;
            }

            .podcast-line {
                width: 2px;
            }

            .podcast-line:nth-child(1) {
                height: 16px;
            }

            .podcast-line:nth-child(2) {
                height: 22px;
            }

            .podcast-line:nth-child(3) {
                height: 12px;
            }

            .podcast-line:nth-child(4) {
                height: 18px;
            }

            .podcast-line:nth-child(5) {
                height: 14px;
            }

            .podcast-line:nth-child(6) {
                height: 20px;
            }

            .podcast-line:nth-child(7) {
                height: 24px;
            }

            .podcast-line:nth-child(8) {
                height: 16px;
            }

            .podcast-line:nth-child(9) {
                height: 10px;
            }

            .podcast-line:nth-child(10) {
                height: 14px;
            }

            .podcast-line:nth-child(11) {
                height: 11px;
            }

            .podcast-line:nth-child(12) {
                height: 18px;
            }

            .podcast-line:nth-child(13) {
                height: 13px;
            }

            .podcast-line:nth-child(14) {
                height: 8px;
            }

            .podcast-line:nth-child(15) {
                height: 6px;
            }

            .audio-time-display {
                font-size: 12px;
                padding: 6px 10px;
                right: 15px;
                bottom: 15px;
            }

            .audio-progress-container {
                padding: 0 15px;
                height: 40px;
            }

            .audio-progress-handle {
                width: 14px;
                height: 14px;
            }

            /* Mobile article styles */
            .mobile-article-container {
                margin-top: 68px;
                padding: 20px;
                direction: rtl;
                background: #fff;
            }

            .mobile-article-category {
                font-size: 16px;
                color: #888;
                margin-bottom: 10px;
                text-align: right;
            }

            .mobile-article-category a {
                color: #888;
                text-decoration: none;
            }

            .mobile-article-title {
                font-size: 28px;
                font-family: asswat-medium;
                color: #141414;
                margin-bottom: 12px;
                line-height: 1.4;
                text-align: right;
            }

            .mobile-article-summary {
                font-size: 16px;
                color: #555;
                line-height: 1.6;
                margin-bottom: 15px;
                text-align: right;
                font-family: asswat-regular;
                font-weight: bold;
            }

            .mobile-article-meta {
                font-size: 16px;
                color: #141414;
                font-family: asswat-light;
                margin-bottom: 15px;
                text-align: right;
                line-height: 1.6;
            }

            .mobile-article-meta a {
                color: #141414;
                text-decoration: none;
            }

            .mobile-article-date-share {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 15px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .mobile-article-date {
                font-size: 14px;
                color: #888;
            }

            .mobile-article-image {
                width: calc(100% + 40px);
                margin: 15px -20px;
                overflow: hidden;
            }

            .mobile-article-image img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
                aspect-ratio: 16/9;
                cursor: pointer;
            }

            .mobile-article-image figcaption {
                font-size: 14px;
                color: #888;
                padding: 8px 20px;
                background: #f9f9f9;
                text-align: right;
            }

            .mobile-article-image figcaption:empty {
                display: none;
            }

            .mobile-article-content {
                font-size: 16px !important;
                font-family: asswat-regular;
                color: #000000;
                line-height: 1.9;
                text-align: right;
                margin-bottom: 30px;
            }

            .mobile-article-content p span {
                font-size: 28px !important;
                font-family: asswat-regular;
                color: #333;
                line-height: 1.9;
                text-align: right;
                margin: 5px 0px;
            }

            .mobile-article-content * {
                font-family: asswat-regular !important;
                direction: rtl !important;
                box-sizing: border-box;
            }

            .mobile-article-content p {
                margin-bottom: 15px;
                text-align: right;
            }

            .mobile-article-content h2,
            .mobile-article-content h4 {
                font-family: asswat-medium !important;
                color: #111 !important;
                text-align: right !important;
                margin-top: 35px !important;
                margin-bottom: 35px !important;
                font-size: 24px !important;
            }

            .mobile-article-content h3,
            .mobile-article-content h3 * {
                color: #000 !important;
                font-size: 16px !important;
                font-family: asswat-bold !important;
                font-weight: normal !important;
                line-height: 1.9 !important;
                text-align: right !important;
            }

            .mobile-article-content h3 {
                margin: 24px 0 !important;
            }

            .mobile-article-content hr {
                border: none !important;
                height: 1px !important;
                background-color: #cacaca !important;
                margin: 24px 0 !important;
            }

            /* Content tables */
            .mobile-article-content .table-wrap {
                width: 100%;
                overflow-x: auto;
                margin: 20px 0;
                -webkit-overflow-scrolling: touch;
                border: 1px solid #ececec;
                border-radius: 0;
            }

            .mobile-article-content table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
                direction: rtl;
                font-size: 14px;
                color: #222;
                background: #f1f1f1;
                min-width: 440px;
            }

            .mobile-article-content .table-wrap > table {
                border: none;
            }

            .mobile-article-content table th,
            .mobile-article-content table td {
                border: 1px solid #ececec;
                padding: 10px 12px;
                text-align: right;
                vertical-align: middle;
                line-height: 1.6;
            }

            .mobile-article-content table th,
            .mobile-article-content table thead td {
                background: #f1f1f1;
                color: #111;
                font-family: asswat-bold !important;
                font-weight: normal;
            }

            .mobile-article-content table td {
                font-family: asswat-medium !important;
                background: #f1f1f1;
            }

            .mobile-article-content table td.td-active,
            .mobile-article-content table th.td-active {
                background: #ddd;
            }

            /* Headings inside a table cell must not add their outside vertical spacing */
            .mobile-article-content table th h1,
            .mobile-article-content table th h2,
            .mobile-article-content table th h3,
            .mobile-article-content table th h4,
            .mobile-article-content table th h5,
            .mobile-article-content table th h6,
            .mobile-article-content table td h1,
            .mobile-article-content table td h2,
            .mobile-article-content table td h3,
            .mobile-article-content table td h4,
            .mobile-article-content table td h5,
            .mobile-article-content table td h6 {
                margin: 0 !important;
            }

            /* Image spacing and captions */
            .mobile-article-content img {
                display: block;
                max-width: 100% !important;
                height: auto !important;
                cursor: pointer;
            }
            /* Mobile: gallery images stay 100% of their (already full-width) container.
               Regular content images keep the original `max-width:100%` behavior so
               smaller images don't get force-stretched. */
            .mobile-article-content .vvc-cgallery-grid img,
            .mobile-article-content .vvc-cgallery-masonry img,
            .mobile-article-content .vvc-cgallery img {
                width: 100% !important;
            }

            /* Content figure styling */
            .mobile-article-content figure {
                width: 100%;
                margin: 25px 0;
            }

            .mobile-article-content figure img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: cover;
            }

            .mobile-article-content figcaption {
                background: #F5F5F5;
                color: #555;
                font-size: 15px;
                padding: 10px;
                text-align: right;
                font-family: asswat-regular;
                direction: rtl;
            }

            .mobile-article-content figcaption:empty {
                display: none;
            }

            /* Video styling within mobile content */
            .mobile-article-content video {
                width: 100%;
                height: auto;
            }

            .mobile-article-content iframe[src*="youtube"],
            .mobile-article-content iframe[src*="vimeo"],
            .mobile-article-content iframe[src*="dailymotion"] {
                width: 100%;
                height: auto;
                aspect-ratio: 16/9;
                border: none;
            }

            /* Facebook Embed Styles within mobile content (posts, reels, videos) */
            .mobile-article-content .fb-post,
            .mobile-article-content .fb-video,
            .mobile-article-content .fb_iframe_widget,
            .mobile-article-content .fb_iframe_widget span {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                line-height: 0 !important;
                margin: 0 auto 20px auto !important;
                overflow: hidden !important;
            }

            .mobile-article-content .fb_iframe_widget iframe,
            .mobile-article-content iframe[src*="facebook.com"] {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                border: none !important;
                height: 100% !important;
            }

            /* X (Twitter) Embed Styles within mobile content */
            .mobile-article-content .twitter-tweet {
                margin: 20px auto !important;
                max-width: 100% !important;
            }

            .mobile-article-content .twitter-tweet iframe {
                max-width: 100% !important;
            }

            .mobile-article-content a {
                color: #000000;
                text-decoration: none;
                position: relative;
                border-bottom: 2px solid #e4f0ef;
                border-radius: 0;
                padding-bottom: 1px;
                display: inline;
                transition: color 0.3s ease;
            }

            .mobile-article-content a::before {
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

            .mobile-article-content a:hover::before {
                height: calc(100% + 2px);
            }

            /* Blockquote */
            .mobile-article-content blockquote {
                width: 100%;
                padding: 40px 20px;
                margin: 30px 0;
                text-align: center;
                position: relative;
                font-family: asswat-medium;
            }

            .mobile-article-content blockquote p span {
                font-size: 28px;
                color: #222;
                line-height: 1.6;
                font-family: asswat-bold !important;
                text-align: center !important;
            }

            .mobile-article-content blockquote p {
                font-size: 28px;
                color: #222;
                line-height: 1.6;
                font-family: asswat-bold !important;
                text-align: center !important;
            }

            .mobile-article-content blockquote::before {
                content: "";
                position: absolute;
                top: 0px;
                right: 0px;
                width: 32px;
                height: 32px;
                background: url('/user/assets/icons/up.png') no-repeat center;
                background-size: contain;
            }

            .mobile-article-content blockquote::after {
                content: "";
                position: absolute;
                bottom: 0px;
                left: 0px;
                width: 32px;
                height: 32px;
                background: url('/user/assets/icons/down.png') no-repeat center;
                background-size: contain;
            }

            /* Prevent blockquote decorative icons on twitter-tweet embeds */
            .mobile-article-content blockquote.twitter-tweet::before,
            .mobile-article-content blockquote.twitter-tweet::after {
                display: none !important;
            }

            /* Reset blockquote styling for twitter-tweet embeds */
            .mobile-article-content blockquote.twitter-tweet {
                padding: 0 !important;
                margin: 20px auto !important;
                text-align: initial !important;
                font-family: inherit !important;
            }

            .mobile-article-content blockquote.twitter-tweet p {
                font-size: inherit !important;
                font-family: inherit !important;
                text-align: initial !important;
            }

            /* Clickable text styling */
            .mobile-article-content p .clickable-term {
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
                padding: 3px;
                box-sizing: border-box;
                transition: transform 0.2s ease, background-color 0.2s ease;
            }

            .mobile-article-tags {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin: 20px 0;
                justify-content: flex-start;
            }

            .mobile-article-tags a {
                display: inline-block;
                padding: 6px 12px;
                background: #f0f0f0;
                color: #333;
                text-decoration: none;
                font-size: 14px;
            }

            .mobile-article-tags a:hover {
                background: #e0e0e0;
            }

            .mobile-writer-card {
                background: #f9f9f9;
                padding: 15px;
                margin: 20px 0;
                text-align: center;
                display: flex;
                flex-direction: column;
                gap: 12px;
                align-items: center;
                direction: rtl;
            }

            .mobile-writer-image {
                width: 100px;
                height: 100px;
                min-width: 100px;
                border-radius: 50%;
                object-fit: cover;
            }

            .mobile-writer-info {
                flex: 1;
                display: flex;
                gap: 0;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
            }

            .mobile-writer-name {
                font-size: 16px;
                font-family: asswat-bold;
                color: #333;
                margin-bottom: 0;
                margin-right: 6px;
            }

            .mobile-writer-bio {
                font-size: 16px;
                color: #888;
                line-height: 1.4;
                margin: 0;
            }

            .mobile-related-section {
                margin-top: 30px;
                padding: 12px 0 34px;
                background-color: #ffffff;
                border-top: none;
            }

            .mobile-related-title {
                font-size: 18px;
                font-family: asswat-bold;
                color: #000;
                margin-bottom: 15px;
                text-align: right;
                padding: 0 16px;
            }

            .mobile-related-carousel {
                display: flex;
                flex-direction: column;
                gap: 12px;
                overflow-x: visible;
                overflow-y: visible;
                scrollbar-width: none;
                direction: rtl;
            }

            .mobile-related-carousel::-webkit-scrollbar {
                display: none;
            }

            .mobile-related-card {
                display: flex;
                flex-direction: column;
                flex: 0 0 auto;
                width: 100%;
                text-decoration: none;
                color: inherit;
                direction: rtl;
                position: relative;
                overflow: hidden;
                background: transparent;
                border-radius: 0;
            }

            .mobile-related-image {
                width: 100%;
                height: 180px;
                object-fit: cover;
                display: block;
                margin-bottom: 0;
            }

            .mobile-related-content {
                flex: 1;
                padding: 12px 0;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }

            .mobile-related-category {
                font-size: 11px;
                color: #888;
                margin-bottom: 6px;
                text-align: right;
            }

            .mobile-related-title-text {
                font-size: 14px;
                font-family: asswat-bold;
                color: #000;
                line-height: 1.5;
                text-align: right;
                word-wrap: break-word;
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

        /* ===== RELATED NEWS CAROUSEL (Mobile) ===== */
        @media (max-width: 768px) {
            .mobile-related-news-wrapper {
                background: #fff;
                height: auto;
                display: flex;
                flex-direction: column;
                position: relative;
                padding: 16px 0;
            }

            .mobile-related-news-badge {
                position: relative;
                top: auto;
                right: auto;
                background: transparent;
                color: #000;
                padding: 0px 0px 10px 16px;
                font-size: 20px;
                line-height: 1;
                border-radius: 0;
                display: block;
                text-align: right;
                z-index: 10;
                font-family: 'asswat-medium' !important;
                font-weight: 400 !important;
            }

            .related-news-track {
                width: 100%;
                height: auto;
                overflow-x: auto;
                overflow-y: hidden;
                display: flex;
                align-items: flex-start;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                direction: rtl;
                gap: 16px;
            }

            .related-news-track::-webkit-scrollbar {
                display: none;
            }

            .related-news-scroll-card {
                flex: 0 0 80vw;
                height: auto;
                scroll-snap-align: start;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            .related-news-scroll-card img {
                width: 100%;
                aspect-ratio: 16 / 9;
                object-fit: cover;
                display: block;
            }

            .related-news-scroll-card-content {
                padding: 12px 0px;
                color: #333;
                text-align: right;
                display: flex;
                flex-direction: column;
                gap: 6px;
                min-height: 80px;
            }

            .related-news-scroll-category {
                font-size: 16px;
                color: #666;
            }

            .related-news-scroll-title {
                font-size: 18px;
                font-weight: 800;
                line-height: 1.3;
                color: #000;
                font-family: 'asswat-bold';
            }

            .related-news-scroll-desc {
                font-size: 13px;
                color: #555;
                line-height: 1.5;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                font-family: 'asswat-regular';
            }
        }

        @media (max-width: 480px) {
            .podcast-lines {
                left: 50px;
                width: 10%;
                height: 25px;
                gap: 2px;
            }

            .podcast-line {
                width: 2px;
            }

            .podcast-line:nth-child(1) {
                height: 14px;
            }

            .podcast-line:nth-child(2) {
                height: 18px;
            }

            .podcast-line:nth-child(3) {
                height: 10px;
            }

            .podcast-line:nth-child(4) {
                height: 15px;
            }

            .podcast-line:nth-child(5) {
                height: 12px;
            }

            .podcast-line:nth-child(6) {
                height: 17px;
            }

            .podcast-line:nth-child(7) {
                height: 20px;
            }

            .podcast-line:nth-child(8) {
                height: 14px;
            }

            .podcast-line:nth-child(9) {
                height: 8px;
            }

            .podcast-line:nth-child(10) {
                height: 12px;
            }

            .podcast-line:nth-child(11) {
                height: 9px;
            }

            .podcast-line:nth-child(12) {
                height: 15px;
            }

            .podcast-line:nth-child(13) {
                height: 11px;
            }

            .podcast-line:nth-child(14) {
                height: 7px;
            }

            .podcast-line:nth-child(15) {
                height: 5px;
            }

            .audio-time-display {
                font-size: 11px;
                padding: 5px 8px;
                right: 10px;
                bottom: 12px;
            }

            .audio-progress-container {
                padding: 0 10px;
                height: 35px;
            }

            .audio-progress-handle {
                width: 12px;
                height: 12px;
            }
        }
    </style>

    {{-- ================= FULLSCREEN IMAGE MODAL ================= --}}
    <div id="fullscreenImageModal" class="fullscreen-image-modal">
        <div class="fullscreen-image-container">
            <button class="fullscreen-image-close" id="fullscreenImageClose" type="button" aria-label="إغلاق"><span class="material-symbols-outlined">close</span></button>
            <button class="fullscreen-image-prev" id="fullscreenImagePrev" type="button"
                aria-label="الصورة السابقة"><span class="material-symbols-outlined">chevron_right</span></button>
            <button class="fullscreen-image-next" id="fullscreenImageNext" type="button"
                aria-label="الصورة التالية"><span class="material-symbols-outlined">chevron_left</span></button>
            <img loading="lazy" decoding="async" id="fullscreenImageContent" src="" alt="صورة بحجم كامل">
            <div class="fullscreen-image-caption" id="fullscreenImageCaption"></div>
            <div class="fullscreen-image-counter" id="fullscreenImageCounter"></div>
        </div>
    </div>

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

    {{-- ================= WEB ================= --}}
    <div class="web">
        @include('user.components.fixed-nav')

        <div class="custom-container">
            <div class="custom-main">

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

                {{-- Title --}}
                <h1 class="custom-article-title">{{ $news->long_title }}</h1>

                {{-- Summary --}}
                <div class="custom-article-summary">{{ $news->summary }}</div>

                {{-- Authors --}}
                <div class="custom-meta">

                    @php
                        $writers = $news->writers;
                    @endphp

                    @if ($writers->count() > 0)
                        @if ($news->city)
                            {{ $news->city->name }} -
                        @endif
                        {{-- First writer with city --}}
                        <span>
                            @if ($writers[0]->pivot->role)
                                <span style="color: #888;">{{ $writers[0]->pivot->role }}:</span>
                            @endif
                        </span>
                        <a href="{{ route('writer.show', $writers[0]->id) }}">

                            {{ $writers[0]->name }}

                        </a>

                        {{-- Other writers, each on a new line --}}
                        @foreach ($writers->slice(1) as $writer)
                            <br>
                            <a href="{{ route('writer.show', $writer->id) }}">
                                <span>
                                    @if ($writer->pivot->role)
                                        <span style="color: #888;">{{ $writer->pivot->role }}:</span>
                                    @endif
                                    {{ $writer->name }}

                                </span>
                            </a>
                        @endforeach
                    @endif

                </div>

                {{-- Date and Share Section --}}
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

                    // Use published_date if available (first publication), otherwise published_at, then created_at
                    $dateToUse = null;
                    if ($news->published_date) {
                        $dateToUse = $news->published_date;
                    } elseif ($news->published_at) {
                        $dateToUse = $news->published_at;
                    } else {
                        $dateToUse = $news->created_at;
                    }

                    // Convert to Carbon instance if it's a string
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

                @php
                    $shareTitle = $news->share_title ?: $news->long_title;
                    $shareDescription = $news->share_description ?: $news->summary;
                    $shareImage = $news->share_image ?: $news->main_image;
                @endphp

                <div style="margin-top: 10px" class="custom-date-share">
                    {{-- Date on the RIGHT --}}
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <p class="date-text">{{ $day }} {{ $month }} {{ $year }} |
                            {{ $time }}</p>

                        @php
                            $totalViews = $news->contentDailyViews->sum('views') ?? 0;
                        @endphp
                        @auth
                            <div class="views-count"
                                style="display: flex; align-items: center; gap: 5px; color: #888; font-size: 14px;">
                                <i class="fas fa-eye"></i>
                                <span>{{ number_format($totalViews) }}</span>
                            </div>
                        @endauth
                    </div>

                    {{-- Share on the LEFT --}}
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

                {{-- Feature Image (hidden for video/album/podcast templates since they use it as cover) --}}
                @if (
                    $news->template !== 'no_image' &&
                        $news->template !== 'video' &&
                        $news->template !== 'album' &&
                        $news->template !== 'podcast')
                    @php $detailMedia = $news->media()->wherePivot('type', 'detail')->first(); @endphp
                    <figure class="custom-article-image-wrapper">
                        <x-responsive-img
                            :src="$detailMedia->path"
                            :alt="$news->caption"
                            sizes="(max-width: 900px) 100vw, 800px"
                            :widths="[400, 800, 1200, 1600]"
                            :default="1200"
                            :eager="true"
                            :priority="true"
                            style="aspect-ratio: 16/9; object-fit: cover; cursor: pointer;"
                            class="feature-image-clickable"
                            :data-full-image="$detailMedia->path"
                        />
                        <figcaption>{{ $news->caption ?? '' }}</figcaption>
                    </figure>
                @endif

                @if ($news->template == 'no_image')
                    @include('user.components.ligne')
                    <br>
                @endif

                {{-- Album --}}
                @if ($news->template == 'album' && $news->media()->wherePivot('type', 'album')->count())
                    @php
                        // Use the article's main image as the first slide (cover) in the album
$mainImage = $news->media()->wherePivot('type', 'main')->first();
$albumImages = $news->media()->wherePivot('type', 'album')->get();

                        // Prepend main image as the first slide if it exists
                        if ($mainImage) {
                            $allAlbumImages = collect([$mainImage])->concat($albumImages);
                        } else {
                            $allAlbumImages = $albumImages;
                        }
                    @endphp
                    @include('user.components.album-slider', [
                        'albumImages' => $allAlbumImages,
                        'caption' => $news->caption ?? '',
                    ])
                @endif

                {{-- Video --}}
                @if ($news->template == 'video' && $news->media()->wherePivot('type', 'video')->first())
                    @php
                        // Use the article's main image as the video poster/thumbnail
$mainImage = $news->media()->wherePivot('type', 'main')->first();
                        $posterImage = $mainImage ? $mainImage->path : null;
                    @endphp
                    @include('user.components.video-player', [
                        'video' => $news->media()->wherePivot('type', 'video')->first()->path,
                        'caption' => $news->media()->wherePivot('type', 'video')->first()->alt ?? 'فيديو',
                        'poster' => $posterImage,
                        'context' => 'web',
                    ])
                @endif

                {{-- Podcast/Audio --}}
                @if ($news->template == 'podcast' && $news->media()->wherePivot('type', 'podcast')->first())
                    @php
                        // Use the article's main image as the audio cover
$mainImage = $news->media()->wherePivot('type', 'main')->first();
$coverImage = $mainImage ? $mainImage->path : null;
$audioPath = $news->media()->wherePivot('type', 'podcast')->first()->path;
                    @endphp

                    {{-- Enhanced Audio Player --}}
                    <div class="audio-player-wrapper" id="audioPlayerWrapper">

                        <div class="audio-player-image" style="position:relative;">
                            <img loading="lazy" decoding="async" src="{{ $coverImage }}" alt="{{ $news->caption ?? 'بودكاست' }}" loading="lazy">
                            {{-- Glassmorphism control bar (content-player style) --}}
                            <div class="audio-glass-bar" onclick="event.stopPropagation()">
                                <div class="audio-play-icon" id="audioPlayIcon">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                                <div class="audio-progress-container" id="audioProgressBarInteractive">
                                    <div class="audio-progress-fill" id="audioProgressFillInteractive"></div>
                                    <div class="audio-progress-handle" id="audioProgressHandle"></div>
                                </div>
                                <div class="audio-time-display" id="audioTimeDisplay">
                                    <span id="currentTime">0:00</span> / <span id="totalDuration">0:00</span>
                                </div>
                            </div>
                        </div>

                        {{-- Audio controls --}}
                        <div class="audio-player-controls">
                            <audio id="podcastAudio" preload="metadata">
                                <source src="{{ $audioPath }}" type="audio/mpeg">
                                متصفحك لا يدعم تشغيل الصوتيات.
                            </audio>
                        </div>

                        {{-- Caption --}}
                        @if ($news->caption)
                            <div class="audio-caption">{{ $news->caption }}</div>
                        @endif
                    </div>
                @endif

                <div class="article-text-wrapper">
                    {{-- Article Content --}}
                    <div class="custom-article-content">{!! $news->content !!}</div>

                    {{-- Tags --}}
                    <div class="custom-tags">
                        @foreach ($news->tags as $tag)
                            <a href="{{ route('tag.show', $tag->id) }}" style="text-decoration: none; color: inherit;">
                                <span>
                                    {{ $tag->name }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    {{-- Writer Card --}}
                    @if ($writers->count() > 0)
                        @foreach ($writers as $writer)
                            @if ($writer->bio != '')
                                <a href="{{ route('writer.show', $writer->id) }}"
                                    style="text-decoration: none; color: inherit;">
                                    <div class="writer-card">
                                        <img loading="lazy" decoding="async" src="{{ $writer->image ?? asset('user.png') }}" alt="{{ $writer->name }}"
                                            loading="lazy"
                                            style="border-radius:50%; width:80px; height:80px; object-fit:cover;">
                                        <div class="writer-info">
                                            <span class="bio"><span class="name">{{ $writer->name }}</span>
                                                {{ $writer->bio }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endif
                            <br>
                        @endforeach
                    @endif
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="custom-sidebar">
                <p class="section-title">الأكثر قراءة</p>
                @include('user.components.ligne')

                <div class="newCategoryReadMoreNews">
                    @foreach ($lastWeekNews as $index => $item)
                        <div class="newCategoryReadMoreNews-list">
                            <span class="number">{{ $index + 1 }}</span>
                            <a href="{{ route('news.show', $item->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $item->title }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>

                @include('user.components.sp60')

                <p class="section-title">المزيد من {{ $news->category?->name }}</p>
                @include('user.components.ligne')

                @foreach ($lastNews as $content)
                    <div class="sp20" style="margin-top: 16px;"></div>
                    <div class="news-card-horizontal-news">
                        <div class="news-card-image-news">
                            <a href="{{ route('news.show', $content->shortlink) }}">
                                <img loading="lazy" decoding="async" src="{{ $content->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/IMG20.jpg' }}"
                                    alt="{{ $content->title ?? 'News' }}" loading="lazy">
                            </a>
                        </div>
                        <div class="news-card-text-news">
                            <a href="{{ route('news.show', $content->shortlink) }}"
                                style="text-decoration: none; color: inherit;">
                                <p>{{ $content->title ?? '' }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Related News Section --}}
        <section class="economy-feature-grid container">
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
        </section>

        @include('user.components.sp60')
        @include('user.components.footer')
    </div>

    <div class="mobile">
        @include('user.mobile.mobile-home')
        <!-- Mobile Article Content -->
        <div class="mobile-article-container">
            <div id="greybar"
                style="background-color: #252525; height: 68px; position: fixed; top: 0; left: 0; right: 0; z-index: 10;">
            </div>
            <!-- Category -->
            <div class="mobile-article-category">
                @if ($news->category && $news->country)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}">
                        {{ $news->category->name ?? '' }}
                    </a>
                    -
                    <a href="{{ route('category.show', ['id' => $news->country->id, 'type' => 'Country']) }}">
                        {{ $news->country->name ?? '' }}
                    </a>
                @elseif ($news->category && $news->continent)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}">
                        {{ $news->category->name ?? '' }}
                    </a>
                    -
                    <a href="{{ route('category.show', ['id' => $news->continent->id, 'type' => 'Continent']) }}">
                        {{ $news->continent->name ?? '' }}
                    </a>
                @elseif ($news->category)
                    <a href="{{ route('category.show', ['id' => $news->category->id, 'type' => 'Category']) }}">
                        {{ $news->category->name ?? '' }}
                    </a>
                @endif
            </div>

            <!-- Title -->
            <h1 class="mobile-article-title">{{ $news->long_title }}</h1>

            <!-- Summary -->
            <div class="mobile-article-summary">{{ $news->summary }}</div>

            <!-- Meta / Writers -->
            <div class="mobile-article-meta">
                @php
                    $writers = $news->writers;
                @endphp

                @if ($writers->count() > 0)
                    @if ($news->city)
                        {{ $news->city->name }} -
                    @endif
                    {{-- First writer with city --}}
                    <span>
                        @if ($writers[0]->pivot->role)
                            <span style="color: #888;">{{ $writers[0]->pivot->role }}:</span>
                        @endif
                    </span>
                    <a href="{{ route('writer.show', $writers[0]->id) }}">
                        {{ $writers[0]->name }}
                    </a>

                    {{-- Other writers, each on a new line --}}
                    @foreach ($writers->slice(1) as $writer)
                        <br>
                        <a href="{{ route('writer.show', $writer->id) }}">
                            <span>
                                @if ($writer->pivot->role)
                                    <span style="color: #888;">{{ $writer->pivot->role }}:</span>
                                @endif
                                {{ $writer->name }}
                            </span>
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- Date and Share -->
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
                $date = $news->created_at;
                $day = $date->format('d');
                $month = $months[$date->format('m')];
                $year = $date->format('Y');
                $time = $date->format('H:i');
            @endphp

            @php
                $shareTitle = $news->share_title ?: $news->long_title;
                $shareDescription = $news->share_description ?: $news->summary;
                $shareImage = $news->share_image ?: $news->main_image;
            @endphp

            <div class="mobile-article-date-share">
                <span class="mobile-article-date">{{ $day }} {{ $month }} {{ $year }} |
                    {{ $time }}</span>

                {{-- Share on the LEFT (matching web version exactly) --}}
                <div class="share-container" id="shareContainerMobile">
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
                        <a href="#" id="copyLinkBtnMobile" title="نسخ الرابط" rel="noopener" class="share-icon">
                            <i class="fa-solid fa-link"></i>
                        </a>
                    </div>

                    {{-- Share Button --}}
                    <button class="share-btn" id="shareToggleMobile" type="button" title="مشاركة"
                        aria-label="زر المشاركة">
                        <img loading="lazy" decoding="async" src="{{ asset('user/assets/icons/send.png') }}" alt="Share" style="width:20px;">
                    </button>
                </div>
            </div>

            <!-- Feature Image -->
            @if ($news->template == 'normal_image')
                @php $mDetailMedia = $news->media()->wherePivot('type', 'detail')->first(); @endphp
                <figure class="mobile-article-image">
                    <x-responsive-img
                        :src="$mDetailMedia->path"
                        :alt="$news->caption"
                        sizes="100vw"
                        :widths="[400, 600, 800, 1200]"
                        :default="800"
                        :eager="true"
                        :priority="true"
                        style="cursor: pointer;"
                    />
                    @if ($news->caption)
                        <figcaption>{{ $news->caption }}</figcaption>
                    @endif
                </figure>
            @endif

            @if ($news->template == 'no_image')
                @include('user.components.ligne')
                <br>
            @endif

            <!-- Album -->
            @if ($news->template == 'album' && $news->media()->wherePivot('type', 'album')->count())
                @php
                    $mainImage = $news->media()->wherePivot('type', 'main')->first();
                    $albumImages = $news->media()->wherePivot('type', 'album')->get();
                    if ($mainImage) {
                        $allAlbumImages = collect([$mainImage])->concat($albumImages);
                    } else {
                        $allAlbumImages = $albumImages;
                    }
                @endphp
                @include('user.components.album-slider', [
                    'albumImages' => $allAlbumImages,
                    'caption' => $news->caption ?? '',
                ])
            @endif

            <!-- Video -->
            @if ($news->template == 'video' && $news->media()->wherePivot('type', 'video')->first())
                @php
                    $mainImage = $news->media()->wherePivot('type', 'main')->first();
                    $posterImage = $mainImage ? $mainImage->path : null;
                @endphp
                @include('user.components.video-player', [
                    'video' => $news->media()->wherePivot('type', 'video')->first()->path,
                    'caption' => $news->media()->wherePivot('type', 'video')->first()->alt ?? 'فيديو',
                    'poster' => $posterImage,
                    'context' => 'mobile',
                ])
            @endif

            <!-- Podcast/Audio -->
            @if ($news->template == 'podcast' && $news->media()->wherePivot('type', 'podcast')->first())
                @php
                    $mainImage = $news->media()->wherePivot('type', 'main')->first();
                    $coverImage = $mainImage ? $mainImage->path : null;
                    $audioPath = $news->media()->wherePivot('type', 'podcast')->first()->path;
                @endphp

                <div class="audio-player-wrapper" id="audioPlayerWrapperMobile">
                    <div class="audio-player-image" style="position:relative;">
                        <img loading="lazy" decoding="async" src="{{ $coverImage }}" alt="{{ $news->caption ?? 'بودكاست' }}" loading="lazy">
                        <div class="audio-glass-bar" onclick="event.stopPropagation()">
                            <div class="audio-play-icon" id="audioPlayIconMobile">
                                <i class="fa-solid fa-play"></i>
                            </div>
                            <div class="audio-progress-container" id="audioProgressBarInteractiveMobile">
                                <div class="audio-progress-fill" id="audioProgressFillInteractiveMobile"></div>
                                <div class="audio-progress-handle" id="audioProgressHandleMobile"></div>
                            </div>
                            <div class="audio-time-display" id="audioTimeDisplayMobile">
                                <span id="currentTimeMobile">0:00</span> / <span id="totalDurationMobile">0:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="audio-player-controls">
                        <audio id="podcastAudioMobile" preload="metadata">
                            <source src="{{ $audioPath }}" type="audio/mpeg">
                            متصفحك لا يدعم تشغيل الصوتيات.
                        </audio>
                    </div>
                    @if ($news->caption)
                        <div class="audio-caption">{{ $news->caption }}</div>
                    @endif
                </div>
            @endif

            <!-- Article Content -->
            <div class="mobile-article-content">{!! $news->content !!}</div>

            <!-- Tags -->
            <div class="mobile-article-tags">
                @foreach ($news->tags as $tag)
                    <a href="{{ route('tag.show', $tag->id) }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>

            <!-- Writer Cards -->
            @php $writers = $news->writers; @endphp
            @if ($writers->count() > 0)
                @foreach ($writers as $writer)
                    @if ($writer->bio != '')
                        <a href="{{ route('writer.show', $writer->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="mobile-writer-card">
                                <img loading="lazy" decoding="async" src="{{ $writer->image ?? asset('user.png') }}" alt="{{ $writer->name }}"
                                    class="mobile-writer-image" loading="lazy">
                                <div class="mobile-writer-info">
                                    <div class="mobile-writer-bio"><span class="mobile-writer-name">{{ $writer->name }}
                                        </span>{{ $writer->bio }}</div>
                                </div>
                            </div>
                        </a>
                        <div style="height: 15px;"></div>
                    @endif
                @endforeach
            @endif


            <!-- Related News Section (Carousel) -->
            @if (isset($relatedNews) && $relatedNews->count() > 0)
                <div class="mobile-related-news-wrapper">
                    <div class="mobile-related-news-badge">ذات صلة</div>
                    @include('user.components.ligne')
                    <div style="height: 8px"></div>
                    <div class="related-news-track" dir="rtl">
                        @foreach ($relatedNews->take(5) as $item)
                            <article class="related-news-scroll-card">
                                <a href="{{ route('news.show', $item->shortlink) }}"
                                    style="text-decoration: none; color: inherit;">
                                    @php
                                        $img =
                                            $item->media()->wherePivot('type', 'detail')->first()?->path ??
                                            ($item->media()->wherePivot('type', 'main')->first()?->path ??
                                                asset($item->image ?? 'user/assets/images/default-post.jpg'));
                                    @endphp
                                    <img loading="lazy" decoding="async" src="{{ $img }}" alt="{{ $item->title }}">
                                </a>
                                <div class="related-news-scroll-card-content">
                                    @if (isset($item->category) && $item->category)
                                        <p class="related-news-scroll-category">
                                            <a href="{{ route('category.show', ['id' => $item->category->id, 'type' => 'Category']) }}"
                                                style="color: inherit; text-decoration: none;">
                                                {{ $item->category->name }}
                                            </a>
                                        </p>
                                    @endif
                                    <a href="{{ route('news.show', $item->shortlink) }}"
                                        style="text-decoration: none; color: inherit;">
                                        <h3 class="related-news-scroll-title">
                                            {{ \Illuminate\Support\Str::limit($item->mobile_title, 51) }}</h3>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Most read (below related) --}}
            @include('user.partials.mobile-most-read', ['items' => $lastWeekNews ?? null])

            {{-- More from the category --}}
            @include('user.partials.mobile-more-from', ['items' => $lastNews ?? null])

        </div>

        <!-- Compact mobile footer at the end -->
        <div class="mobile-container">
            @include('user.mobile.footer')
        </div>
    </div>





    {{-- ================= COMPREHENSIVE JAVASCRIPT ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeShareFunctionality();
            initializeTextDefinitionModal();
            initializeCopyLink();
            initializeAudioPlayer();
            initializeContentAudioPlayers();
            enhanceContentTables();
            initializeGreybarScroll();
            initializeMobileFeatureImage();
            initializeMobileGallery();
            initializeFeatureImage();
            initializeGallery();
        });

        /**
         * Initialize Greybar Hide on Scroll
         */
        function initializeGreybarScroll() {
            const greybar = document.getElementById('greybar');
            if (!greybar) return;

            window.addEventListener('scroll', function() {
                const footer = document.querySelector('footer');
                if (!footer) return;

                const greybarRect = greybar.getBoundingClientRect();
                const footerRect = footer.getBoundingClientRect();

                // Hide greybar only when it's about to overlap with footer
                if (footerRect.top < greybarRect.bottom) {
                    greybar.classList.add('hide');
                } else {
                    greybar.classList.remove('hide');
                }
            });
        }

        /**
         * Initialize Enhanced Audio Player
         */
        function initializeAudioPlayer() {
            // Initialize both web and mobile audio players
            initializeSingleAudioPlayer('audioPlayerWrapper', 'podcastAudio', 'audioPlayIcon',
                'audioProgressBarInteractive', 'audioProgressFillInteractive', 'audioProgressHandle',
                'currentTime', 'totalDuration');

            initializeSingleAudioPlayer('audioPlayerWrapperMobile', 'podcastAudioMobile', 'audioPlayIconMobile',
                'audioProgressBarInteractiveMobile', 'audioProgressFillInteractiveMobile', 'audioProgressHandleMobile',
                'currentTimeMobile', 'totalDurationMobile');
        }

        /**
         * Wrap every content table in a horizontally-scrollable container so wide
         * tables never overflow the page on small screens. Also strips the inline
         * border/width attributes TinyMCE adds so the CSS styling can take over.
         */
        function enhanceContentTables() {
            document.querySelectorAll('.custom-article-content table, .mobile-article-content table').forEach((table) => {
                if (table.dataset.tblEnhanced) return;
                table.dataset.tblEnhanced = '1';
                table.removeAttribute('border');
                table.removeAttribute('width');
                table.style.removeProperty('width');
                if (!table.parentElement.classList.contains('table-wrap')) {
                    const wrap = document.createElement('div');
                    wrap.className = 'table-wrap';
                    table.parentNode.insertBefore(wrap, table);
                    wrap.appendChild(table);
                }

                // Cells that contain bold text get the "active" (hover-like) background
                table.querySelectorAll('td, th').forEach((cell) => {
                    let bold = !!cell.querySelector('strong, b');
                    if (!bold) {
                        [cell, ...cell.querySelectorAll('*')].forEach((el) => {
                            const fw = window.getComputedStyle(el).fontWeight;
                            if (fw === 'bold' || fw === 'bolder' || parseInt(fw, 10) >= 600) bold = true;
                        });
                    }
                    cell.classList.toggle('td-active', bold);
                });
            });
        }

        /**
         * Build a simple custom UI for every <audio> inside the article content
         * (both web and mobile). The native element is kept as the hidden engine.
         */
        function initializeContentAudioPlayers() {
            const audios = document.querySelectorAll('.custom-article-content audio, .mobile-article-content audio');

            const fmt = (s) => {
                if (!isFinite(s) || s < 0) return '0:00';
                s = Math.floor(s);
                const m = Math.floor(s / 60);
                return m + ':' + String(s % 60).padStart(2, '0');
            };

            const PLAY_SVG = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>';
            const PAUSE_SVG = '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>';

            audios.forEach((audio) => {
                if (audio.dataset.audEnhanced) return;
                audio.dataset.audEnhanced = '1';
                audio.removeAttribute('controls');

                const player = document.createElement('div');
                player.className = 'aud-player';
                player.innerHTML =
                    '<button type="button" class="aud-play" aria-label="تشغيل">' + PLAY_SVG + '</button>' +
                    '<span class="aud-time aud-cur">0:00</span>' +
                    '<div class="aud-bar"><div class="aud-fill"></div><div class="aud-knob"></div></div>' +
                    '<span class="aud-time aud-dur">0:00</span>';
                audio.parentNode.insertBefore(player, audio);

                const playBtn = player.querySelector('.aud-play');
                const bar = player.querySelector('.aud-bar');
                const fill = player.querySelector('.aud-fill');
                const knob = player.querySelector('.aud-knob');
                const curEl = player.querySelector('.aud-cur');
                const durEl = player.querySelector('.aud-dur');

                const setDur = () => { durEl.textContent = fmt(audio.duration); };
                audio.addEventListener('loadedmetadata', setDur);
                if (audio.readyState >= 1) setDur();

                audio.addEventListener('timeupdate', () => {
                    const p = audio.duration ? (audio.currentTime / audio.duration) : 0;
                    fill.style.width = (p * 100) + '%';
                    knob.style.left = (p * 100) + '%';
                    curEl.textContent = fmt(audio.currentTime);
                });

                audio.addEventListener('play', () => {
                    // Pause any other content audio that is playing
                    audios.forEach((a) => { if (a !== audio && !a.paused) a.pause(); });
                    playBtn.innerHTML = PAUSE_SVG;
                    playBtn.setAttribute('aria-label', 'إيقاف');
                });
                audio.addEventListener('pause', () => {
                    playBtn.innerHTML = PLAY_SVG;
                    playBtn.setAttribute('aria-label', 'تشغيل');
                });
                audio.addEventListener('ended', () => {
                    playBtn.innerHTML = PLAY_SVG;
                    fill.style.width = '0%';
                    knob.style.left = '0%';
                });

                playBtn.addEventListener('click', () => {
                    if (audio.paused) audio.play(); else audio.pause();
                });

                const seek = (clientX) => {
                    const r = bar.getBoundingClientRect();
                    const ratio = Math.min(Math.max((clientX - r.left) / r.width, 0), 1);
                    if (audio.duration) audio.currentTime = ratio * audio.duration;
                };
                bar.addEventListener('click', (e) => seek(e.clientX));

                // Drag the knob / bar to scrub
                let dragging = false;
                const onMove = (e) => { if (dragging) seek(e.touches ? e.touches[0].clientX : e.clientX); };
                const stop = () => { dragging = false; };
                bar.addEventListener('mousedown', (e) => { dragging = true; seek(e.clientX); });
                document.addEventListener('mousemove', onMove);
                document.addEventListener('mouseup', stop);
                bar.addEventListener('touchstart', (e) => { dragging = true; seek(e.touches[0].clientX); }, { passive: true });
                document.addEventListener('touchmove', onMove, { passive: true });
                document.addEventListener('touchend', stop);
            });
        }

        /**
         * Initialize a single audio player instance
         */
        function initializeSingleAudioPlayer(wrapperId, audioId, playIconId, progressBarId, progressFillId, handleId,
            currentTimeId, totalDurationId) {
            const audioPlayerWrapper = document.getElementById(wrapperId);
            const audioElement = document.getElementById(audioId);
            const playIcon = document.getElementById(playIconId);
            const audioProgressBarInteractive = progressBarId ? document.getElementById(progressBarId) : null;
            const audioProgressFillInteractive = progressFillId ? document.getElementById(progressFillId) : null;
            const audioProgressHandle = handleId ? document.getElementById(handleId) : null;
            const currentTimeDisplay = document.getElementById(currentTimeId);
            const totalDurationDisplay = document.getElementById(totalDurationId);

            if (!audioPlayerWrapper || !audioElement) return;

            let isDragging = false;

            // Format time helper
            function formatTime(seconds) {
                if (!seconds || isNaN(seconds)) return '0:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins}:${secs.toString().padStart(2, '0')}`;
            }

            // Update progress bar and time display
            function updateProgress() {
                if (audioElement.duration && audioProgressFillInteractive && audioProgressHandle) {
                    const percent = (audioElement.currentTime / audioElement.duration) * 100;
                    audioProgressFillInteractive.style.width = percent + '%';
                    audioProgressHandle.style.left = percent + '%';
                }

                // Update time displays
                if (currentTimeDisplay) {
                    currentTimeDisplay.textContent = formatTime(audioElement.currentTime);
                }
                if (totalDurationDisplay && audioElement.duration) {
                    totalDurationDisplay.textContent = formatTime(audioElement.duration);
                }
            }

            // Seek to position
            function seekToPosition(clientX) {
                const rect = audioProgressBarInteractive.getBoundingClientRect();
                const pos = (clientX - rect.left) / rect.width;
                const clampedPos = Math.max(0, Math.min(1, pos));

                if (audioElement.duration) {
                    audioElement.currentTime = clampedPos * audioElement.duration;

                    // Update UI immediately
                    const percent = clampedPos * 100;
                    audioProgressFillInteractive.style.width = percent + '%';
                    audioProgressHandle.style.left = percent + '%';
                }
            }

            // Click on progress bar
            if (audioProgressBarInteractive) {
                audioProgressBarInteractive.addEventListener('click', function(e) {
                    seekToPosition(e.clientX);
                });

                // Mouse drag handlers
                audioProgressHandle.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    isDragging = true;
                    audioProgressHandle.classList.add('dragging');
                });

                document.addEventListener('mousemove', function(e) {
                    if (isDragging) {
                        seekToPosition(e.clientX);
                    }
                });

                document.addEventListener('mouseup', function() {
                    if (isDragging) {
                        isDragging = false;
                        audioProgressHandle.classList.remove('dragging');
                    }
                });

                // Touch drag handlers for mobile
                audioProgressHandle.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    isDragging = true;
                    audioProgressHandle.classList.add('dragging');
                });

                document.addEventListener('touchmove', function(e) {
                    if (isDragging && e.touches.length > 0) {
                        seekToPosition(e.touches[0].clientX);
                    }
                });

                document.addEventListener('touchend', function() {
                    if (isDragging) {
                        isDragging = false;
                        audioProgressHandle.classList.remove('dragging');
                    }
                });
            }

            // Toggle play/pause
            function togglePlayPause() {
                if (audioElement.paused) {
                    audioElement.play();
                    audioPlayerWrapper.classList.add('playing');
                    playIcon.innerHTML = '<i class="fa-solid fa-pause"></i>';
                } else {
                    audioElement.pause();
                    audioPlayerWrapper.classList.remove('playing');
                    playIcon.innerHTML = '<i class="fa-solid fa-play"></i>';
                }
            }

            // Event listeners
            playIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                togglePlayPause();
            });

            audioPlayerWrapper.querySelector('.audio-player-image').addEventListener('click', function() {
                togglePlayPause();
            });

            audioElement.addEventListener('timeupdate', updateProgress);

            audioElement.addEventListener('loadedmetadata', function() {
                updateProgress();
            });

            audioElement.addEventListener('ended', function() {
                audioPlayerWrapper.classList.remove('playing');
                playIcon.innerHTML = '<i class="fa-solid fa-play"></i>';
            });
        }

        /**
         * Initialize Share Functionality
         */
        function initializeShareFunctionality() {
            // Initialize web share
            initializeSingleShare('shareContainer', 'shareToggle');
            // Initialize mobile share
            initializeSingleShare('shareContainerMobile', 'shareToggleMobile');
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
            // Initialize mobile copy link
            initializeSingleCopyLink('copyLinkBtnMobile');
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

                    // Add click handler to open image in fullscreen without caption
                    modalImage.onclick = function() {
                        openSingleImage(this.src, '');
                    };
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

        // ================= FULLSCREEN IMAGE GALLERY FUNCTIONALITY =================
        const fullscreenModal = document.getElementById('fullscreenImageModal');
        const fullscreenImageContent = document.getElementById('fullscreenImageContent');
        const fullscreenImageCaption = document.getElementById('fullscreenImageCaption');
        const fullscreenImageCounter = document.getElementById('fullscreenImageCounter');
        const fullscreenImageClose = document.getElementById('fullscreenImageClose');
        const fullscreenImagePrev = document.getElementById('fullscreenImagePrev');
        const fullscreenImageNext = document.getElementById('fullscreenImageNext');

        // Gallery state
        let galleryImages = [];
        let currentImageIndex = 0;
        let isGalleryMode = false; // Track if we're in gallery mode or single image mode

        // Initialize feature image (principal image)
        function initializeFeatureImage() {
            const featureImage = document.querySelector('.feature-image-clickable');
            if (featureImage) {
                featureImage.addEventListener('click', function() {
                    const fullImagePath = this.getAttribute('data-full-image');
                    
                    // Get caption from figure/figcaption only (from editor HTML)
                    let caption = '';
                    const figure = this.closest('figure');
                    if (figure) {
                        const figcaption = figure.querySelector('figcaption');
                        if (figcaption) {
                            caption = figcaption.textContent.trim();
                        }
                    }

                    if (fullImagePath) {
                        openSingleImage(fullImagePath, caption);
                    }
                });
            }
        }

        // Initialize mobile feature image (for mobile article)
        function initializeMobileFeatureImage() {
            const mobileFeatureImage = document.querySelector('.mobile-article-image img');
            if (mobileFeatureImage) {
                mobileFeatureImage.addEventListener('click', function() {
                    const imagePath = this.src;
                    
                    // Get caption from figure/figcaption
                    let caption = '';
                    const figure = this.closest('figure');
                    if (figure) {
                        const figcaption = figure.querySelector('figcaption');
                        if (figcaption) {
                            caption = figcaption.textContent.trim();
                        }
                    }

                    if (imagePath) {
                        openSingleImage(imagePath, caption);
                    }
                });
            }
        }

        // Initialize content images gallery
        function initializeGallery() {
            galleryImages = [];

            // Add all content images only (excluding feature image and "اقرأ أيضاً" cards)
            const contentImages = document.querySelectorAll('.custom-article-content img');
            contentImages.forEach(img => {
                // Skip images that belong to a "read more" block
                if (img.closest('.read-more-block')) return;
                // Skip content-gallery slider images: they open their own scoped viewer
                if (img.closest('.vvc-content-gallery') || img.closest('.vvc-cgallery')) return;

                const figure = img.closest('figure');
                const caption = figure ? (figure.querySelector('figcaption')?.textContent?.trim() || '') : '';

                galleryImages.push({
                    src: img.src,
                    caption: caption,
                    element: img
                });
            });

            // Add click handlers to all gallery images
            galleryImages.forEach((imageData, index) => {
                imageData.element.addEventListener('click', function(e) {
                    e.preventDefault();
                    openGallery(index);
                });
            });
        }

        // Initialize mobile content images gallery
        function initializeMobileGallery() {
            let mobileGalleryImages = [];

            // Add all mobile content images (excluding feature image and "اقرأ أيضاً" cards)
            const mobileContentImages = document.querySelectorAll('.mobile-article-content img');
            mobileContentImages.forEach(img => {
                if (img.closest('.read-more-block')) return;
                // Skip content-gallery slider images: they open their own scoped viewer
                if (img.closest('.vvc-content-gallery') || img.closest('.vvc-cgallery')) return;

                const figure = img.closest('figure');
                const caption = figure ? (figure.querySelector('figcaption')?.textContent?.trim() || '') : '';

                mobileGalleryImages.push({
                    src: img.src,
                    caption: caption,
                    element: img
                });
            });

            // Add click handlers to all mobile gallery images
            mobileGalleryImages.forEach((imageData, index) => {
                imageData.element.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Merge both web and mobile gallery images
                    galleryImages = mobileGalleryImages;
                    openGallery(index);
                });
            });
        }

        // Open single image (for feature image)
        function openSingleImage(imagePath, caption) {
            isGalleryMode = false;
            fullscreenImageContent.src = imagePath;

            // Show or hide caption based on whether caption exists
            if (caption && caption.trim() !== '') {
                // Apply quote replacement to caption
                const captionWithQuotes = caption.replace(/"([^"]*)"/g, '«$1»');
                fullscreenImageCaption.textContent = captionWithQuotes;
                fullscreenImageCaption.style.display = 'block';
            } else {
                fullscreenImageCaption.style.display = 'none';
            }

            // Hide navigation controls for single image
            fullscreenImagePrev.style.display = 'none';
            fullscreenImageNext.style.display = 'none';
            fullscreenImageCounter.style.display = 'none';

            fullscreenModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Open gallery at specific index (for content images)
        function openGallery(index) {
            isGalleryMode = true;
            currentImageIndex = index;
            showCurrentImage();
            fullscreenModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            updateNavigationButtons();
        }

        // Open the fullscreen viewer for a content gallery (vvc slider) using its own
        // set of images, so navigation stays scoped to that gallery. Exposed globally
        // because the slider is built in a separate script.
        window.openContentGallery = function(images, index) {
            if (!Array.isArray(images) || !images.length) return;
            galleryImages = images;
            openGallery(Math.min(Math.max(index || 0, 0), images.length - 1));
        };

        // Show current image in gallery
        function showCurrentImage() {
            if (galleryImages.length === 0) return;

            const currentImage = galleryImages[currentImageIndex];
            fullscreenImageContent.src = currentImage.src;
            
            // Show or hide caption based on whether caption exists
            if (currentImage.caption && currentImage.caption.trim() !== '') {
                // Apply quote replacement to caption
                const captionWithQuotes = currentImage.caption.replace(/"([^"]*)"/g, '«$1»');
                fullscreenImageCaption.textContent = captionWithQuotes;
                fullscreenImageCaption.style.display = 'block';
            } else {
                fullscreenImageCaption.style.display = 'none';
            }

            // Update counter (1-based index for display)
            fullscreenImageCounter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;

            updateNavigationButtons();
        }

        // Update navigation button states
        function updateNavigationButtons() {
            if (!isGalleryMode) return;

            fullscreenImagePrev.disabled = currentImageIndex === galleryImages.length - 1;
            fullscreenImageNext.disabled = currentImageIndex === 0;

            // Hide buttons if only one image
            if (galleryImages.length <= 1) {
                fullscreenImagePrev.style.display = 'none';
                fullscreenImageNext.style.display = 'none';
                fullscreenImageCounter.style.display = 'none';
            } else {
                fullscreenImagePrev.style.display = 'flex';
                fullscreenImageNext.style.display = 'flex';
                fullscreenImageCounter.style.display = 'block';
            }
        }

        // Navigate to previous image
        function showPreviousImage() {
            if (!isGalleryMode) return;
            if (currentImageIndex < galleryImages.length - 1) {
                currentImageIndex++;
                showCurrentImage();
            }
        }

        // Navigate to next image
        function showNextImage() {
            if (!isGalleryMode) return;
            if (currentImageIndex > 0) {
                currentImageIndex--;
                showCurrentImage();
            }
        }

        // Close modal
        function closeFullscreenImageModal() {
            fullscreenModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Event listeners
        fullscreenImageClose.addEventListener('click', closeFullscreenImageModal);
        fullscreenImagePrev.addEventListener('click', showPreviousImage);
        fullscreenImageNext.addEventListener('click', showNextImage);

        // Close only via the X button (no backdrop / Escape close)

        // Keyboard navigation (arrows only; Escape does not close)
        document.addEventListener('keydown', function(e) {
            if (fullscreenModal.classList.contains('active') && isGalleryMode) {
                if (e.key === 'ArrowLeft') {
                    showNextImage();
                } else if (e.key === 'ArrowRight') {
                    showPreviousImage();
                }
            }
        });

        // Prevent modal container click from closing modal
        fullscreenModal.querySelector('.fullscreen-image-container').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Add an "expand" icon to the bottom-right of each content image
        function addExpandIcons() {
            // Only the main (feature) image gets the expand icon
            const imgs = document.querySelectorAll('.feature-image-clickable');
            imgs.forEach(img => {
                if (img.closest('.read-more-block')) return;
                if (img.parentElement && img.parentElement.classList.contains('content-img-wrap')) return;

                const wrap = document.createElement('span');
                wrap.className = 'content-img-wrap';
                img.parentNode.insertBefore(wrap, img);
                wrap.appendChild(img);

                const icon = document.createElement('span');
                icon.className = 'material-symbols-outlined content-img-expand';
                icon.textContent = 'expand_content';
                wrap.appendChild(icon);

                // Only the icon opens fullscreen — block direct image clicks
                img.style.cursor = 'default';
                let allowOpen = false;
                img.addEventListener('click', function(e) {
                    if (!allowOpen) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                    }
                }, true);
                icon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    allowOpen = true;
                    img.click();
                    allowOpen = false;
                });
            });
        }

        // Initialize both feature image and gallery when page loads
        initializeFeatureImage();
        initializeGallery();
        addExpandIcons();
    </script>



    {{-- ================= DYNAMIC READMORE LOADER ================= --}}
    @include('components.readmore-loader')

    {{-- ================= IN-CONTENT GALLERY SLIDER ================= --}}
    <style>
        /* High-specificity overrides — article-content sets img{height:auto!important} which would stack our images */
        .custom-article-content .vvc-cgallery,
        .mobile-article-content  .vvc-cgallery,
        .vvc-cgallery{position:relative;width:100%;max-width:100%;display:block;background:transparent;border:0;border-radius:0;overflow:hidden;margin:25px 0;color:#fff;font-family:inherit;direction:rtl;contain:layout style;box-sizing:border-box;}
        .vvc-cgallery *{box-sizing:border-box;}
        /* Article content forces font-family on all descendants with !important — undo it for FA icons inside the slider */
        .custom-article-content .vvc-cgallery i[class*="fa-"],
        .mobile-article-content  .vvc-cgallery i[class*="fa-"],
        .vvc-cgallery i[class*="fa-"]{font-family:"Font Awesome 6 Free","Font Awesome 6 Brands","FontAwesome" !important;font-weight:900 !important;font-style:normal !important;line-height:1 !important;}
        .vvc-cgallery i.fa-spin{animation:vvc-cg-spin 1s linear infinite;}

        .vvc-cgallery .vvc-cgs-stage{position:relative !important;width:100% !important;aspect-ratio:16/10;background:#000;overflow:hidden;isolation:isolate;}
        /* Fallback height for browsers without aspect-ratio */
        @supports not (aspect-ratio:1/1){ .vvc-cgallery .vvc-cgs-stage{height:60vw;max-height:520px;} }

        .vvc-cgallery .vvc-cgs-track{position:absolute;inset:0;display:flex;width:100%;height:100%;transition:transform .45s cubic-bezier(.4,.0,.2,1);will-change:transform;}
        .custom-article-content .vvc-cgallery .vvc-cgs-slide,
        .mobile-article-content  .vvc-cgallery .vvc-cgs-slide,
        .vvc-cgallery .vvc-cgs-slide{position:relative !important;flex:0 0 100% !important;width:100% !important;height:100% !important;margin:0 !important;background:#000;display:block;}
        .custom-article-content .vvc-cgallery .vvc-cgs-img,
        .mobile-article-content  .vvc-cgallery .vvc-cgs-img,
        .vvc-cgallery .vvc-cgs-img{position:absolute !important;inset:0 !important;width:100% !important;height:100% !important;max-width:100% !important;object-fit:contain !important;margin:0 !important;display:block !important;opacity:0;transition:opacity .35s ease;}
        .vvc-cgallery .vvc-cgs-img.is-loaded{opacity:1;}

        .vvc-cgallery .vvc-cgs-spinner{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.7);font-size:1.6rem;pointer-events:none;opacity:0;transition:opacity .2s;}
        .vvc-cgallery .vvc-cgs-slide.is-loading .vvc-cgs-spinner{opacity:1;}
        .vvc-cgallery .vvc-cgs-spinner i{animation:vvc-cg-spin 1s linear infinite;}
        .vvc-cgallery .vvc-cgs-slide.is-failed::after{content:"\f127";font-family:"Font Awesome 6 Free";font-weight:900;position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.35);font-size:2.2rem;}
        @keyframes vvc-cg-spin{to{transform:rotate(360deg);}}

        .vvc-cgallery .vvc-cgs-progress{position:absolute;top:10px;inset-inline-end:14px;display:flex;gap:4px;z-index:3;max-width:60%;flex-wrap:wrap;justify-content:flex-end;}
        .vvc-cgallery .vvc-cgs-progress span{display:block;width:20px;height:3px;background:rgba(255,255,255,.28);border-radius:0;transition:background .2s, width .25s;cursor:pointer;}
        .vvc-cgallery .vvc-cgs-progress span.is-active{background:#fff;width:28px;}

        .vvc-cgallery .vvc-cgs-arrow{position:absolute;top:50%;transform:translateY(-50%);width:34px;height:34px;background:transparent;border:0;color:#fff;font-size:1rem;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:50%;text-shadow:0 1px 4px rgba(0,0,0,.6);transition:background .18s ease, color .18s ease, text-shadow .18s ease, transform .15s ease;z-index:4;}
        .vvc-cgallery .vvc-cgs-arrow:hover{background:rgba(0,0,0,.6);text-shadow:none;}
        .vvc-cgallery .vvc-cgs-arrow:active{transform:translateY(-50%) scale(.92);}
        .vvc-cgallery .vvc-cgs-arrow.vvc-cgs-prev{left:8px;}
        .vvc-cgallery .vvc-cgs-arrow.vvc-cgs-next{right:8px;}

        .vvc-cgallery .vvc-cgs-toggle{display:none;}

        .vvc-cgallery .vvc-cgs-caption-wrap{position:absolute;left:0;right:0;bottom:0;z-index:3;padding:1.8rem 1.2rem .9rem;background:linear-gradient(to top,rgba(0,0,0,.92) 0%,rgba(0,0,0,.78) 40%,rgba(0,0,0,.35) 75%,rgba(0,0,0,0) 100%);color:#fff;pointer-events:none;transition:opacity .25s ease;}
        .vvc-cgallery .vvc-cgs-caption-wrap:empty,
        .vvc-cgallery .vvc-cgs-caption-wrap.is-empty{display:none;}
        .vvc-cgallery.is-collapsed .vvc-cgs-caption-wrap{opacity:0;}
        .vvc-cgallery .vvc-cgs-caption{font-size:18px;line-height:1.55;color:#fff;margin:0;font-family:'asswat-medium' !important;font-weight:400;animation:vvc-cg-fade .35s ease both;}
        .vvc-cgallery .vvc-cgs-source{font-size:16px;line-height:1.55;color:rgba(255,255,255,.92);margin-top:.5rem;font-family:'asswat-medium' !important;}
        @keyframes vvc-cg-fade{from{opacity:0;transform:translateY(4px);}to{opacity:1;transform:none;}}

        .vvc-cgallery .vvc-cgs-foot{display:none;}

        @media (max-width:640px){
            .vvc-cgallery .vvc-cgs-arrow{width:30px;height:30px;font-size:.9rem;}
            .vvc-cgallery .vvc-cgs-stage{aspect-ratio:4/3;}
            @supports not (aspect-ratio:1/1){ .vvc-cgallery .vvc-cgs-stage{height:75vw;} }
            .vvc-cgallery .vvc-cgs-caption-wrap{padding:1.2rem .9rem .65rem;}
        }

        /* ----- Grid & Masonry layouts ----- */
        .custom-article-content .vvc-cgallery-grid,
        .mobile-article-content  .vvc-cgallery-grid,
        .vvc-cgallery-grid{display:grid;width:100%;max-width:100%;grid-template-columns:repeat(3,1fr);gap:10px;margin:25px 0;direction:rtl;box-sizing:border-box;}
        @media (max-width:760px){ .vvc-cgallery-grid{grid-template-columns:repeat(2,1fr);} }
        @media (max-width:420px){ .vvc-cgallery-grid{grid-template-columns:1fr;} }
        .vvc-cgallery-grid figure{margin:0;position:relative;border-radius:6px;overflow:hidden;background:#0b1320;cursor:zoom-in;}
        .custom-article-content .vvc-cgallery-grid figure img,
        .mobile-article-content  .vvc-cgallery-grid figure img,
        .vvc-cgallery-grid figure img{width:100% !important;height:100% !important;aspect-ratio:1/1;object-fit:cover !important;display:block;transition:transform .35s ease;margin:0 !important;}
        .vvc-cgallery-grid figure:hover img{transform:scale(1.04);}
        .vvc-cgallery-grid figcaption{position:absolute;left:0;right:0;bottom:0;padding:.55rem .7rem;background:linear-gradient(transparent,rgba(0,0,0,.78));color:#fff;font-size:.82rem;line-height:1.4;}
        .vvc-cgallery-grid figcaption .vvc-cgs-src{display:block;font-size:.7rem;color:#cfd6e2;margin-top:.2rem;}

        .custom-article-content .vvc-cgallery-masonry,
        .mobile-article-content  .vvc-cgallery-masonry,
        .vvc-cgallery-masonry{columns:3 240px;column-gap:10px;width:100%;max-width:100%;margin:25px 0;direction:rtl;box-sizing:border-box;}
        .vvc-cgallery-masonry figure{margin:0 0 10px;break-inside:avoid;position:relative;border-radius:6px;overflow:hidden;background:#0b1320;cursor:zoom-in;}
        .custom-article-content .vvc-cgallery-masonry figure img,
        .mobile-article-content  .vvc-cgallery-masonry figure img,
        .vvc-cgallery-masonry figure img{width:100% !important;height:auto !important;display:block;transition:transform .35s ease;margin:0 !important;}
        .vvc-cgallery-masonry figure:hover img{transform:scale(1.03);}
        .vvc-cgallery-masonry figcaption{position:absolute;left:0;right:0;bottom:0;padding:.55rem .7rem;background:linear-gradient(transparent,rgba(0,0,0,.78));color:#fff;font-size:.82rem;line-height:1.4;}
        .vvc-cgallery-masonry figcaption .vvc-cgs-src{display:block;font-size:.7rem;color:#cfd6e2;margin-top:.2rem;}

        /* Lightbox for grid/masonry */
        .vvc-cglb{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:99999;display:none;align-items:center;justify-content:center;direction:rtl;}
        .vvc-cglb.is-open{display:flex;}
        .vvc-cglb img{max-width:92vw;max-height:80vh;object-fit:contain;border-radius:4px;box-shadow:0 10px 30px rgba(0,0,0,.5);}
        .vvc-cglb .vvc-cglb-cap{position:absolute;left:0;right:0;bottom:0;padding:1rem 1.5rem;color:#fff;text-align:center;background:linear-gradient(transparent,rgba(0,0,0,.7));font-size:.95rem;}
        .vvc-cglb .vvc-cglb-cap .vvc-cgs-src{display:block;font-size:.8rem;color:#cfd6e2;margin-top:.25rem;}
        .vvc-cglb button{position:absolute;top:50%;transform:translateY(-50%);width:48px;height:54px;border:0;background:rgba(255,255,255,.12);color:#fff;font-size:1.4rem;cursor:pointer;}
        .vvc-cglb button:hover{background:rgba(255,255,255,.22);}
        .vvc-cglb .vvc-cglb-prev{left:14px;}
        .vvc-cglb .vvc-cglb-next{right:14px;}
        .vvc-cglb .vvc-cglb-close{top:14px;right:14px;transform:none;width:42px;height:42px;border-radius:50%;font-size:1.2rem;}
        .vvc-cglb .vvc-cglb-counter{position:absolute;top:18px;left:18px;color:#cfd6e2;font-size:.9rem;font-variant-numeric:tabular-nums;}
    </style>
    <script>
        (function () {
            function parseItems(node) {
                const raw = node.getAttribute('data-vvc-gallery') || '';
                if (!raw) return [];
                const tries = [raw];
                if (raw.indexOf('%') !== -1) {
                    try { tries.push(decodeURIComponent(raw)); } catch (_) {}
                }
                if (raw.indexOf('&') !== -1) {
                    tries.push(raw.replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&'));
                }
                for (const s of tries) {
                    try { const v = JSON.parse(s); if (Array.isArray(v)) return v; } catch (_) {}
                }
                return [];
            }

            const escAttr = (s) => String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const escHtml = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

            // Shared lightbox for grid/masonry views
            let lb = null, lbState = { items: [], index: 0 };
            function ensureLightbox() {
                if (lb) return lb;
                lb = document.createElement('div');
                lb.className = 'vvc-cglb';
                lb.innerHTML = `
                    <span class="vvc-cglb-counter"></span>
                    <button type="button" class="vvc-cglb-close" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button>
                    <button type="button" class="vvc-cglb-prev"  aria-label="السابق"><i class="fa-solid fa-chevron-left"></i></button>
                    <img alt=""/>
                    <button type="button" class="vvc-cglb-next"  aria-label="التالي"><i class="fa-solid fa-chevron-right"></i></button>
                    <div class="vvc-cglb-cap"></div>`;
                document.body.appendChild(lb);
                const imgEl = lb.querySelector('img');
                const capEl = lb.querySelector('.vvc-cglb-cap');
                const cntEl = lb.querySelector('.vvc-cglb-counter');
                const showIdx = (i) => {
                    const n = lbState.items.length; if (!n) return;
                    lbState.index = (i + n) % n;
                    const it = lbState.items[lbState.index] || {};
                    imgEl.src = it.u || '';
                    imgEl.alt = it.a || it.t || '';
                    const t = it.t ? escHtml(it.t) : '';
                    const a = (it.a && it.a !== it.t) ? `<span class="vvc-cgs-src">${escHtml(it.a)}</span>` : '';
                    capEl.innerHTML = t + a;
                    cntEl.textContent = (lbState.index + 1) + ' | ' + n;
                };
                lb.querySelector('.vvc-cglb-prev').addEventListener('click', () => showIdx(lbState.index + 1)); // RTL: prev = next index
                lb.querySelector('.vvc-cglb-next').addEventListener('click', () => showIdx(lbState.index - 1));
                lb.querySelector('.vvc-cglb-close').addEventListener('click', () => lb.classList.remove('is-open'));
                lb.addEventListener('click', (e) => { if (e.target === lb) lb.classList.remove('is-open'); });
                document.addEventListener('keydown', (e) => {
                    if (!lb.classList.contains('is-open')) return;
                    if (e.key === 'Escape') lb.classList.remove('is-open');
                    else if (e.key === 'ArrowLeft')  showIdx(lbState.index + 1);
                    else if (e.key === 'ArrowRight') showIdx(lbState.index - 1);
                });
                lb._show = showIdx;
                return lb;
            }
            function openLightbox(items, index) {
                ensureLightbox();
                lbState.items = items;
                lb.classList.add('is-open');
                lb._show(index || 0);
            }

            function buildGridOrMasonry(node, items, layout) {
                const wrap = document.createElement('div');
                wrap.className = layout === 'masonry' ? 'vvc-cgallery-masonry' : 'vvc-cgallery-grid';
                wrap.innerHTML = items.map((it, i) => {
                    const cap = it.t ? escHtml(it.t) : '';
                    const src = (it.a && it.a !== it.t) ? `<span class="vvc-cgs-src">${escHtml(it.a)}</span>` : '';
                    const figCap = (cap || src) ? `<figcaption>${cap}${src}</figcaption>` : '';
                    return `<figure data-idx="${i}"><img src="${escAttr(it.u || '')}" alt="${escAttr(it.a || it.t || '')}" loading="lazy" referrerpolicy="no-referrer"/>${figCap}</figure>`;
                }).join('');
                node.replaceWith(wrap);
                wrap.querySelectorAll('figure').forEach(fig => {
                    fig.addEventListener('click', () => openLightbox(items, parseInt(fig.dataset.idx, 10) || 0));
                });
            }

            function buildSlider(node) {
                const items = parseItems(node);
                if (!items.length) { node.remove(); return; }

                const wrap = document.createElement('div');
                wrap.className = 'vvc-cgallery';
                wrap.innerHTML = `
                    <div class="vvc-cgs-stage" aria-roledescription="carousel">
                        <div class="vvc-cgs-progress" aria-hidden="true">
                            ${items.map((_, i) => `<span data-idx="${i}"></span>`).join('')}
                        </div>
                        <div class="vvc-cgs-track">
                            ${items.map((it, i) => `
                                <div class="vvc-cgs-slide is-loading" data-idx="${i}">
                                    <img class="vvc-cgs-img" alt="${escAttr(it.a || it.t || '')}" src="${escAttr(it.u || '')}" loading="${i === 0 ? 'eager' : 'lazy'}" referrerpolicy="no-referrer"/>
                                    <div class="vvc-cgs-spinner" aria-hidden="true"><i class="fa-solid fa-spinner fa-spin"></i></div>
                                </div>`).join('')}
                        </div>
                        <button type="button" class="vvc-cgs-arrow vvc-cgs-prev" aria-label="السابق"><i class="fa-solid fa-chevron-left"></i></button>
                        <button type="button" class="vvc-cgs-arrow vvc-cgs-next" aria-label="التالي"><i class="fa-solid fa-chevron-right"></i></button>
                        <button type="button" class="vvc-cgs-toggle" aria-label="إخفاء الوصف"><i class="fa-solid fa-chevron-down"></i></button>
                    </div>
                    <div class="vvc-cgs-caption-wrap">
                        <p class="vvc-cgs-caption"></p>
                        <div class="vvc-cgs-source" hidden></div>
                    </div>
                    <div class="vvc-cgs-foot">
                        <button type="button" class="vvc-cgs-play" aria-label="تشغيل تلقائي"><i class="fa-solid fa-play"></i></button>
                        <span class="vvc-cgs-counter"></span>
                    </div>`;
                node.replaceWith(wrap);

                // Size the stage to the images' natural aspect ratio (all images are
                // expected to be the same size), so nothing is cropped or letter-boxed.
                const stageEl = wrap.querySelector('.vvc-cgs-stage');
                function applyNaturalRatio(img) {
                    if (!stageEl || !img || !img.naturalWidth || !img.naturalHeight) return;
                    // Stage spans the full content width; its height follows the image's
                    // natural ratio, so the image fills it edge-to-edge with no black bars
                    // and no cropping (portrait images just make a taller gallery).
                    stageEl.style.aspectRatio = img.naturalWidth + '/' + img.naturalHeight;
                }

                const track   = wrap.querySelector('.vvc-cgs-track');
                const slides  = Array.from(wrap.querySelectorAll('.vvc-cgs-slide'));
                const imgs    = slides.map(s => s.querySelector('.vvc-cgs-img'));
                const dots    = Array.from(wrap.querySelectorAll('.vvc-cgs-progress span'));
                const caption = wrap.querySelector('.vvc-cgs-caption');
                const source  = wrap.querySelector('.vvc-cgs-source');
                const counter = wrap.querySelector('.vvc-cgs-counter');
                const playBtn = wrap.querySelector('.vvc-cgs-play');
                const toggle  = wrap.querySelector('.vvc-cgs-toggle');
                const stage   = wrap.querySelector('.vvc-cgs-stage');
                const total   = items.length;
                let index = 0, autoTimer = null, playing = false;

                // Fullscreen viewer payload for this gallery (kept scoped to its own images)
                const fsImages = items.map(it => ({
                    src: it.u || '',
                    caption: (it.t && String(it.t).trim()) ? it.t : (it.a || '')
                }));

                // Wire load/error per image: fade in once decoded, hide spinner on either outcome
                imgs.forEach((img, i) => {
                    const slide = slides[i];
                    const done  = () => { img.classList.add('is-loaded'); slide.classList.remove('is-loading'); if (i === 0) applyNaturalRatio(img); };
                    const fail  = () => { slide.classList.remove('is-loading'); slide.classList.add('is-failed'); };
                    // Attach listeners FIRST so we never miss a race with already-cached images
                    img.addEventListener('load',  done);
                    img.addEventListener('error', fail);
                    if (img.complete) {
                        if (img.naturalWidth > 0) done(); else fail();
                    }
                    // Click a slide image to open the fullscreen viewer at that image
                    img.style.cursor = 'zoom-in';
                    img.addEventListener('click', () => {
                        if (typeof window.openContentGallery === 'function') {
                            window.openContentGallery(fsImages, i);
                        }
                    });
                });

                function updateTrack() {
                    // RTL track: slide 0 sits at the right; translate positive X to move toward higher indices
                    track.style.transform = `translateX(${index * 100}%)`;
                }

                const captionWrap = wrap.querySelector('.vvc-cgs-caption-wrap');
                function show(i) {
                    index = (i + total) % total;
                    updateTrack();
                    dots.forEach((el, k) => el.classList.toggle('is-active', k === index));
                    const cur = items[index] || {};
                    const hasTitle  = !!(cur.t && String(cur.t).trim());
                    const hasSource = !!(cur.a && cur.a !== cur.t && String(cur.a).trim());
                    caption.textContent = hasTitle ? cur.t : '';
                    if (hasSource) {
                        source.hidden = false;
                        source.textContent = cur.a;
                    } else {
                        source.hidden = true;
                        source.textContent = '';
                    }
                    if (captionWrap) captionWrap.classList.toggle('is-empty', !hasTitle && !hasSource);
                }

                function play() {
                    if (playing || total < 2) return;
                    playing = true;
                    playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    playBtn.setAttribute('aria-label', 'إيقاف');
                    autoTimer = setInterval(() => show(index + 1), 4000);
                }
                function pause() {
                    playing = false;
                    playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                    playBtn.setAttribute('aria-label', 'تشغيل تلقائي');
                    if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
                }

                wrap.querySelector('.vvc-cgs-prev').addEventListener('click', () => { pause(); show(index + 1); });
                wrap.querySelector('.vvc-cgs-next').addEventListener('click', () => { pause(); show(index - 1); });
                dots.forEach(d => d.addEventListener('click', () => { pause(); show(parseInt(d.dataset.idx, 10)); }));
                playBtn.addEventListener('click', () => (playing ? pause() : play()));
                toggle.addEventListener('click', () => wrap.classList.toggle('is-collapsed'));

                // Touch swipe with live drag feedback (RTL)
                let touchX = null, dragOffset = 0;
                stage.addEventListener('touchstart', (e) => {
                    touchX = e.touches[0].clientX;
                    track.style.transition = 'none';
                }, { passive: true });
                stage.addEventListener('touchmove', (e) => {
                    if (touchX == null) return;
                    dragOffset = e.touches[0].clientX - touchX;
                    track.style.transform = `translateX(calc(${index * 100}% + ${dragOffset}px))`;
                }, { passive: true });
                stage.addEventListener('touchend', () => {
                    if (touchX == null) return;
                    track.style.transition = '';
                    if (Math.abs(dragOffset) > 50) { pause(); show(index + (dragOffset < 0 ? 1 : -1)); }
                    else updateTrack();
                    touchX = null; dragOffset = 0;
                });

                // Keyboard
                wrap.tabIndex = 0;
                wrap.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft')  { pause(); show(index + 1); }
                    if (e.key === 'ArrowRight') { pause(); show(index - 1); }
                });

                show(0);
            }

            function init(root) {
                (root || document).querySelectorAll('.vvc-content-gallery[data-vvc-gallery]').forEach(buildSlider);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => init());
            } else {
                init();
            }
        })();
    </script>

@endsection
