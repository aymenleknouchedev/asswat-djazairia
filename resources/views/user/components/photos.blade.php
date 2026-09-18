@php
    $excludedTopIds = isset($topContents)
        ? $topContents->pluck('content_id')->toArray()
        : \App\Models\TopContent::pluck('content_id')->toArray();
    $photosSliderItems = \App\Models\Content::where('section_id', \App\Models\Section::where('name', 'صور')->value('id'))
        ->where('status', 'published')
        ->whereNotIn('id', $excludedTopIds)
        ->orderByDesc('published_date')
        ->take(12)
        ->get();
    $firstPhoto = $photosSliderItems->first() ?? $firstPhoto;
    $photosData = $photosSliderItems->map(function ($p) {
        return [
            'title' => $p->title,
            'shortlink' => $p->shortlink,
            'category' => $p->section ? $p->section->name : null,
            'summary' => $p->summary,
            'image' => optional($p->media()->wherePivot('type', 'main')->first())->path,
        ];
    })->values();
@endphp
<script>window.__photosData = @json($photosData);</script>
<div class="photos-feature">
    <a href="{{ route('news.show', $firstPhoto->shortlink) }}">
        <div class="image-wrapper">
            <img loading="lazy" decoding="async" id="photoImage" src="{{ $firstPhoto->media()->wherePivot('type', 'main')->first()->path }}"
                alt="Feature Algeria">
            <div class="corner-icon">
                @include('user.icons.image')
            </div>
        </div>
    </a>

    <div class="content">
        <h3 id="photoCategory">
            <x-category-links :content="$firstPhoto" />
        </h3>
        <a href="{{ route('news.show', $firstPhoto->shortlink) }}" style="text-decoration: none; color: inherit;">
            <h2 id="photoTitle">{{ $firstPhoto->title }}</h2>
        </a>
        <p class="article-desc" id="photoDescription">{{ $firstPhoto->summary }}</p>
    </div>
</div>

<style>
    .photos-feature img,
    .photos-feature .content {
        transition: opacity 0.5s ease;
    }

    .fade-out {
        opacity: 0;
    }

    .photos-feature {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
    }

    .photos-feature .image-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        overflow: hidden;
    }

    .photos-feature img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        aspect-ratio: 16 / 9;
    }

    .photos-feature .corner-icon {
        position: absolute;
        bottom: 15px;
        left: 20px;
        width: 45px;
        height: 45px;
        color: white;
    }

    .photos-feature .corner-icon img {
        width: 100%;
        height: 100%;
    }

    .photos-feature .content {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 20px;
    }

    .photos-feature .content h3 {
        margin: 0;
        color: #999;
        font-size: 12px;
        font-family: asswat-light;
        font-weight: lighter;
        cursor: pointer;
    }

    .photos-feature .content h2 {
        margin: 10px 0 10px;
        font-size: 24px;
        line-height: 1.3;
        font-family: asswat-bold;
        cursor: pointer;
        transition: .2s;
    }

    .photos-feature .content p {
        margin: 0;
        font-size: 17px;
        line-height: 1.6;
        color: #555;
    }

    .photos-feature .content h2:hover {
        text-decoration: underline;
    }

    .fade-out {
        opacity: 0;
    }

    .fade-in {
        opacity: 1;
    }

    #backArrow.disabled,
    #nextArrow.disabled {
        opacity: 0.4;
        cursor: default;
        pointer-events: none;
    }
</style>
