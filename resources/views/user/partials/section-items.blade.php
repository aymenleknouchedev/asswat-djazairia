@foreach ($moreContents as $content)
    <div style="margin-bottom: 15px" class="newCategory-all-card">
        <div class="newCategory-all-card-image">
            <a href="{{ route('news.show', $content->shortlink) }}">
                <x-responsive-img
                    :src="$content->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/IMG19.jpg'"
                    :alt="$content->title ?? 'News Image'"
                    sizes="(max-width: 600px) 100vw, (max-width: 1024px) 50vw, 400px"
                    :widths="[400, 600, 800]"
                    :default="600"
                />
            </a>
        </div>
        <div class="newCategory-all-card-text">
            <h3> <x-category-links :content="$content" />
            </h3>
            <a href="{{ route('news.show', $content->shortlink) }}" style="text-decoration: none; color: inherit;">
                <h2>{{ $content->title ?? '' }}</h2>
            </a>
            <p class="article-desc">{{ $content->summary ?? '' }}</p>
            <x-content-date :content="$content" />
        </div>
    </div>
@endforeach
