@if (Auth::check())
    <div class="admin-top-bar">
        <div class="site-container admin-bar-content">
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
                @if (isset($writer))
                    <a href="{{ route('dashboard.writer.edit', $writer->id) }}" class="btn btn-sm btn-warning"
                        title="تعديل">
                        <i class="fas fa-pencil"></i>
                    </a>
                @endif

                <a href="{{ route('dashboard.content.create') }}" class="btn btn-sm btn-warning" title="إضافة خبر">
                    <i class="fa-solid fa-plus"></i>
                </a>

                <a href="{{ route('dashboard.breakingnew.create') }}" class="admin-action-breaking" title="إضافة عاجل">
                    <i class="fa-solid fa-plus"></i>
                </a>

                <a href="{{ route('dashboard.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    title="تسجيل الخروج">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                </a>
                <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <style>
        .admin-top-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 36px;
            background: #F5F5F5;
            color: #333;
            font-size: 14px;
            /* Above the hero header (3000) and slide-out panel (2001) so admin
               controls stay visible/usable on trend & window hero pages too. */
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

        body {
            padding-top: 36px !important;
        }

        #site-main-nav,
        #site-subnav,
        #site-breaking-news {
            margin-top: 36px;
        }

        @media (max-width: 992px) {
            body {
                padding-top: 0px !important;
            }
        }
    </style>
@endif
