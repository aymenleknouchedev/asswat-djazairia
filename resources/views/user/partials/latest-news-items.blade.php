@foreach ($latestContents as $item)
    <div class="newCategory-all-card">
        <div class="newCategory-all-card-date">
            <h4 style="width: 140px">
                @php
                    \Carbon\Carbon::setLocale('ar');
                    $created = \Carbon\Carbon::parse($item->published_date);
                    $now = \Carbon\Carbon::now();
                    $diffHours = $created->diffInHours($now);
                    $months = [
                        '01' => 'جانفي', '02' => 'فيفري', '03' => 'مارس', '04' => 'أفريل',
                        '05' => 'ماي', '06' => 'جوان', '07' => 'جويلية', '08' => 'أوت',
                        '09' => 'سبتمبر', '10' => 'أكتوبر', '11' => 'نوفمبر', '12' => 'ديسمبر',
                    ];
                @endphp

                @if ($diffHours < 24)
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <span>{{ $created->diffForHumans(null, null, false, 1) }}</span>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; align-items: flex-start;">
                        <span>{{ $created->format('d') }} {{ $months[$created->format('m')] }} {{ $created->format('Y') }}</span>
                        <span style="color: #74747C;">{{ $created->format('H:i') }}</span>
                    </div>
                @endif
            </h4>
        </div>
        <div class="newCategory-all-card-image">
            <a href="{{ route('news.show', $item->shortlink) }}">
                <x-responsive-img
                    :src="$item->media()->wherePivot('type', 'main')->first()->path ?? './user/assets/images/IMG20.jpg'"
                    :alt="$item->title"
                    sizes="(max-width: 600px) 100vw, (max-width: 1024px) 50vw, 400px"
                    :widths="[400, 600, 800]"
                    :default="600"
                />
            </a>
        </div>
        <div class="newCategory-all-card-text">
            <h3><x-category-links :content="$item" /></h3>
            <a href="{{ route('news.show', $item->shortlink) }}" style="text-decoration: none; color: inherit;">
                <h2>{{ $item->title }}</h2>
            </a>
            <p class="article-desc">{{ $item->summary }}</p>
        </div>
    </div>
@endforeach
