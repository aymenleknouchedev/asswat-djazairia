@foreach ($articles as $item)
    <div class="newCategory-all-card">
        <!-- Image -->
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

        <!-- Text -->
        <div class="newCategory-all-card-text">
            <h3>{{ $item->category?->name ?? '' }}</h3>
            <a href="{{ route('news.show', $item->shortlink) }}"
                style="text-decoration: none; color: inherit;">
                <h2>{{ $item->title }}</h2>
            </a>
            <p class="article-desc">{{ $item->summary }}</p>
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
