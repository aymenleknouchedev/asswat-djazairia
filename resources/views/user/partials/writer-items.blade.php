@foreach ($articles as $item)
    <div class="newCategory-all-card">
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
            <h4 style="width: 140px">{{ $day }} {{ $month }} {{ $year }}</h4>
        </div>

        <div class="newCategory-all-card-image">
            <a href="{{ route('news.show', $item->shortlink) }}">
                <img loading="lazy" decoding="async" src="{{ $item->media()->wherePivot('type', 'main')->first()->path ?? asset('user/assets/images/IMG20.jpg') }}"
                    alt="{{ $item->title }}">
            </a>
        </div>

        <div class="newCategory-all-card-text">
            <h3><x-category-links :content="$item" /></h3>
            <a href="{{ route('news.show', $item->shortlink) }}"
                style="text-decoration: none; color: inherit;">
                <h2>{{ $item->title }}</h2>
            </a>
            <p class="article-desc">{{ $item->summary }}</p>
        </div>
    </div>
@endforeach
