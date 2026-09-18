    <!-- === Main Navigation === -->
    <nav id="site-main-nav">
        <div class="site-container">
            <div class="site-nav-right">
                <a href="{{ route('index') }}">
                    <img loading="eager" decoding="async" class="site-logo" src="{{ asset('user/assets/images/logo.svg') }}" alt="Logo">
                </a>
                <ul class="site-nav-links">
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'algeria' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'algeria']) }}">الجزائر</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'world' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'world']) }}">عالم</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'economy' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'economy']) }}">اقتصاد</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'sports' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'sports']) }}">رياضة</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'people' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'people']) }}">ناس</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'culture' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'culture']) }}">ثقافة وفنون</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'technology' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'technology']) }}">تكنولوجيا</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'health' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'health']) }}">صحة</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'environment' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'environment']) }}">بيئة</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'media' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'media']) }}">ميديا</a>
                    </li>
                    <li
                        class="site-nav-link {{ request()->routeIs('newSection') && request()->route('section') === 'variety' ? 'active' : '' }}">
                        <a href="{{ route('newSection', ['section' => 'variety']) }}">منوعات</a>
                    </li>
                    <li class="site-nav-link {{ request()->routeIs('latestNews') ? 'active' : '' }}"
                        id="site-show-subnav"><a href="{{ route('latestNews') }}">المزيد <i
                                class="fa-solid fa-chevron-down nav-arrow"></i>
                        </a></li>
                </ul>
            </div>
            <div class="site-nav-left">
                <form action="{{ route('search') }}" method="GET" class="site-search-form">
                    <input name="query" type="text" class="site-search-input" placeholder="ابحث...">
                    <button type="submit" class="search-icon" style="background:none;border:none;padding:0;">
                        @include('user.icons.search')
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- === Breaking News === -->
    <nav id="site-breaking-news">
        <div class="site-container site-breaking-news-container">
            <a href="{{ route('breakingNews') }}" style="text-decoration: none; color: inherit; flex: 1; display: flex; align-items: center; gap: 10px;">
                <div class="site-breaking-news-content">
                    <span class="site-breaking-label" style="font-family: asswat-bold;">عاجل</span>
                    <p class="site-breaking-text" id="site-breaking-text"></p>
                </div>
            </a>
            <button class="site-close-breaking" aria-label="إغلاق">✖</button>
        </div>
    </nav>

    <!-- === Secondary Navigation === -->
    <nav id="site-secondary-nav">
        <div class="site-container site-secondary-container">
            <div class="site-secondary-content">
                <div class="site-latest-news">
                    <span class="site-latest-label">آخر الأخبار</span>
                    <p class="site-latest-text" id="site-latest-text"></p>
                </div>
                <div class="site-weather-widget">
                    <div class="site-weather-location">الجزائر</div>
                    <div class="site-weather-item">
                        <img loading="lazy" decoding="async" src="{{ asset('user/assets/icons/day-mode.png') }}" alt="نهار">
                        <span>{{ isset($weather['day']) ? $weather['day'] . '°' : '26°' }}</span>
                    </div>
                    <div class="site-weather-divider"></div>
                    <div class="site-weather-item">
                        <img loading="lazy" decoding="async" src="{{ asset('user/assets/icons/night-mode.png') }}" alt="ليل">
                        <span>{{ isset($weather['night']) ? $weather['night'] . '°' : '15°' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- === Subnav === -->
    <nav id="site-subnav">
        <div class="site-container">
            <ul class="site-subnav-links">
                <li class="site-subnav-link {{ request()->routeIs('reviews') ? 'active' : '' }}">
                    <a href="{{ route('reviews') }}">آراء</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('windows') ? 'active' : '' }}">
                    <a href="{{ route('windows') }}">نوافذ</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('files') ? 'active' : '' }}">
                    <a href="{{ route('files') }}">ملفات</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('investigation') ? 'active' : '' }}">
                    <a href="{{ route('investigation') }}">فحص</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('videos') ? 'active' : '' }}">
                    <a href="{{ route('videos') }}">فيديو</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('podcasts') ? 'active' : '' }}">
                    <a href="{{ route('podcasts') }}">بودكاست</a>
                </li>
                <li class="site-subnav-link {{ request()->routeIs('photos') ? 'active' : '' }}">
                    <a href="{{ route('photos') }}">صور</a>
                </li>
            </ul>
        </div>
    </nav>



    @include('user.components.admin-top-bar')
