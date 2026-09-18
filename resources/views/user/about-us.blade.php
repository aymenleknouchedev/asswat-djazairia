@extends('layouts.index')

@section('title', 'من نحن | أصوات')

@section('content')

    <style>
        /* Mobile first - Show only mobile content */
        .web {
            display: none;
        }

        .mobile {
            display: block;
        }

        /* Show web content on desktop */
        @media (min-width: 992px) {
            .web {
                display: block !important;
            }

            .mobile {
                display: none !important;
            }
        }

        /* ============== NAVIGATION BAR STYLING ============== */
        .nav-wrapper {
            background-color: #f3f3f3;
            padding: 0;
        }

        .grey-bar {
            background-color: #252525;
            height: 68px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            transition: transform 0.3s ease, opacity 0.2s ease;
        }

        .grey-bar.bar-hidden {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        .mobile-about-flow {
            margin-top: 68px;
        }

        /* ============== DESKTOP STYLES ============== */
        .about-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        .about-hero {
            text-align: center;
            margin-bottom: 4rem;
        }

        .about-hero h1 {
            font-size: 2.5rem;
            font-weight: 900;
            color: #000000;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .about-hero p {
            font-size: 1.25rem;
            color: #000000;
            max-width: 800px;
            margin: 0 auto;
        }

        .about-section {
            margin-bottom: 5rem;
        }

        .about-section-content {
            max-width: 650px;
            margin: 0 auto;
        }

        .about-section h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 2rem;
            line-height: 1.3;
        }

        .about-section p {
            font-size: 1.125rem;
            color: #000000;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .about-section p:last-child {
            margin-bottom: 0;
        }

        .about-divider {
            margin: 4rem 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .about-section p.semibold {
            font-weight: 600;
        }

        /* ============== MOBILE STYLES ============== */
        @media (max-width: 991px) {
            .about-container {
                max-width: 100%;
                padding: 1.5rem 1rem 4rem;
                margin: 0;
            }

            .nav-wrapper {
                background-color: #f3f3f3;
                padding: 0;
            }

            .about-hero {
                margin-bottom: 2rem;
            }

            .about-hero h1 {
                font-size: 1.75rem;
                margin-bottom: 0.75rem;
                line-height: 1.3;
            }

            .about-hero p {
                font-size: 1rem;
                max-width: 100%;
            }

            .about-section {
                margin-bottom: 2.5rem;
            }

            .about-section-content {
                max-width: 100%;
                padding: 0 0.5rem;
            }

            .about-section h2 {
                font-size: 1.375rem;
                margin-bottom: 1.25rem;
                line-height: 1.4;
            }

            .about-section p {
                font-size: 0.95rem;
                line-height: 1.7;
                margin-bottom: 1rem;
                text-align: right;
            }

            .about-divider {
                margin: 2rem 0;
                border-top: 1px solid #d5d5d5;
            }
        }

        /* ============== EXTRA SMALL DEVICES ============== */
        @media (max-width: 480px) {
            .about-container {
                padding: 1rem 0.75rem 4rem;
            }

            .about-hero h1 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .about-hero p {
                font-size: 0.9rem;
            }

            .about-section {
                margin-bottom: 2rem;
            }

            .about-section-content {
                padding: 0;
            }

            .about-section h2 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .about-section p {
                font-size: 0.9rem;
                line-height: 1.65;
                margin-bottom: 0.85rem;
            }
        }
    </style>

    <!-- Fixed Navigation - Desktop -->
    <div class="web">
        <div class="nav-wrapper">
            @include('user.components.fixed-nav')
        </div>

        <!-- Content Container for Desktop -->
        <div class="about-container">
            <!-- Hero Section -->
            <div class="about-hero">
                <h1>«أصوات جزائرية»</h1>
                <p>المشروع والرؤية</p>
            </div>

            <!-- About Section 1 -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>الفكرة والمشروع</h2>
                    <p>
                        كانت فكرة الصوت حاضرةً طيلة الفترة التي عكفنا فيها على صياغة مشروع «أصوات جزائرية» وتجسيده، ومنها
                        أتت
                        تسميتُه وهويتُه ورؤيتُه: الصوت بوصفه علامةً حاملة لخصوصية، علامةً يمكن تمييزها في محيط واسع ومفتوح
                        على
                        كلّ ما يُسمع، وكثيرٌ منه لا يؤدّي بالضرورة إلى معنىً.
                    </p>
                    <p>
                        واليوم، يتبدّى عالمُنا، أكثر من أيّ وقت مضى، كأحداث متلاحقة تصمّ آذاننا بعنف وقوعها، ما يجعل منه
                        فضاءً
                        ضوضائيّاً ومؤذياً، حتّى أنّنا كثيراً ما نشعر بالاغتراب فيه، خصوصًا إذا كنّا ننتمي إلى «جنوب العالم».
                    </p>
                    <p>
                        انصبّ اهتمامنا على التفكير في مساحة تُرتّب هذه الجلبة، وتُعيد صياغة أصدائها في خبر، في قصّة، في
                        فكرة، أو
                        في قراءة لمشهد. نحاول أن نستلّ من نشاز العالم نصوصاً إخبارية تخاطب قارئاً نبيهاً، ذلك أنّنا نؤمن أنّ
                        الصوت إنّما وُجد ليُسمَع ويُدرَك، لا ليضيع في الكون هباءً منثوراً.
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="about-divider">

            <!-- About Section 2 -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>المحتوى والالتزام</h2>
                    <p>
                        نُفسح المجال في «أصوات جزائرية»، إضافة إلى الخبر الذي يقول ما حدث، للقصص الصحفية والتغطيات الميدانية
                        والحوارات والتحليلات النقدية المعمّقة، ضمن التزام بالقيَم المهنية واحترام لعقل المتلقّي وذكائه
                        وحساسيته.
                    </p>
                    <p>
                        كما نُولي اهتمامًا خاصًّا بيوميات الناس وحكاياتهم وتجاربهم الإنسانية، وبالذاكرة السياسية والاجتماعية
                        والثقافية.
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="about-divider">

            <!-- About Section 3 -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>الصورة والرسالة</h2>
                    <p>
                        ولأنّ الصوت لا يكتمل إلّا بها، لم تغِب الصورة أيضًا عن تصوُّرنا. ومنها أتى شعارُ الموقع: «كُن في
                        الصورة»؛ مختصِرًا مهمّتنا البسيطة والمعقّدة في آن: محاولة وضْع الجمهور في صورة ما يحدث محلّيًّا
                        وإقليميًّا ودوليًّا، مِن خلال محتوًى إعلامي متوازن ورصين.
                    </p>
                    <p>
                        نتوخّى الجودة في المحتوى والشكل، ونتقصّى الحقيقة، وننقل الأصوات والآراء والمواقف، على اختلافها،
                        بأمانة
                        وموضوعية.
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="about-divider">

            <!-- About Section 4 -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>الرؤية والحرية</h2>
                    <p>
                        اخترنا شهر نوفمبر، ذكرى اندلاع الثورة الجزائرية، تاريخًا لانطلاق «أصوات جزائرية»، استلهامًا للثورة
                        وتضحياتها وقيَمها في منطقة دفعت وتدفع أثمانًا باهظة في سبيل تحرُّرها، من الجزائر إلى فلسطين، وأكبرُ
                        تلك
                        القيَم هي الحرّية التي نُدرك ألّا صحافة من دونها.
                    </p>
                    <p>
                        وقد كان قصدُنا أن نقول إنّ موقعنا وموقعكم يطمح لأن يكون فضاءً جامعًا لأصوات تحرُّرية لا عدد لها،
                        تلتقي
                        في مواجهة مقولات الهيمنة الاستعمارية ونقض أدواتها المعرفية ومغالطاتها.
                    </p>
                    <p>
                        وإنّنا نصبو لنتشارك مع بقية شعوب العالم مساحة حرّة غير محكومة بالهيمنة والمركزيات والخدائع
                        والاستغلال.
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="about-divider">

            <!-- About Section 5 -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>رأس المال</h2>
                    <p>
                        أمّا رأس المال الذي ننطلق منه؛ فهو ثقتكم، كتّابًا وقرّاء وشركاء ومؤمنين بأنّ الحرّية وحدها تمنح
                        الأصوات
                        معنًى.
                    </p>
                    <p class="semibold">
                        لتُسمَع وتُدرَك وتُلمَس، لتُكتَب وتُقرأ وتُرى.
                    </p>
                </div>
            </div>
        </div>

        @include('user.components.footer')

    </div>

    <!-- Mobile Navigation -->
    <div class="mobile">
        @include('user.mobile.mobile-home')

        <!-- Grey navigation bar -->
        <div class="grey-bar"></div>

        <!-- Mobile About Flow -->
        <div class="mobile-about-flow">
            <!-- Content Container for Mobile -->
            <div class="about-container">
                <!-- Hero Section -->
                <div class="about-hero">
                    <h1>«أصوات جزائرية»</h1>
                    <p>المشروع والرؤية</p>
                </div>

                <!-- About Section 1 -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>الفكرة والمشروع</h2>
                        <p>
                            كانت فكرة الصوت حاضرةً طيلة الفترة التي عكفنا فيها على صياغة مشروع «أصوات جزائرية» وتجسيده،
                            ومنها أتت
                            تسميتُه وهويتُه ورؤيتُه: الصوت بوصفه علامةً حاملة لخصوصية، علامةً يمكن تمييزها في محيط واسع
                            ومفتوح على
                            كلّ ما يُسمع، وكثيرٌ منه لا يؤدّي بالضرورة إلى معنىً.
                        </p>
                        <p>
                            واليوم، يتبدّى عالمُنا، أكثر من أيّ وقت مضى، كأحداث متلاحقة تصمّ آذاننا بعنف وقوعها، ما يجعل منه
                            فضاءً
                            ضوضائيّاً ومؤذياً، حتّى أنّنا كثيراً ما نشعر بالاغتراب فيه، خصوصًا إذا كنّا ننتمي إلى «جنوب
                            العالم».
                        </p>
                        <p>
                            انصبّ اهتمامنا على التفكير في مساحة تُرتّب هذه الجلبة، وتُعيد صياغة أصدائها في خبر، في قصّة، في
                            فكرة، أو
                            في قراءة لمشهد. نحاول أن نستلّ من نشاز العالم نصوصاً إخبارية تخاطب قارئاً نبيهاً، ذلك أنّنا نؤمن
                            أنّ
                            الصوت إنّما وُجد ليُسمَع ويُدرَك، لا ليضيع في الكون هباءً منثوراً.
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="about-divider">

                <!-- About Section 2 -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>المحتوى والالتزام</h2>
                        <p>
                            نُفسح المجال في «أصوات جزائرية»، إضافة إلى الخبر الذي يقول ما حدث، للقصص الصحفية والتغطيات
                            الميدانية
                            والحوارات والتحليلات النقدية المعمّقة، ضمن التزام بالقيَم المهنية واحترام لعقل المتلقّي وذكائه
                            وحساسيته.
                        </p>
                        <p>
                            كما نُولي اهتمامًا خاصًّا بيوميات الناس وحكاياتهم وتجاربهم الإنسانية، وبالذاكرة السياسية
                            والاجتماعية
                            والثقافية.
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="about-divider">

                <!-- About Section 3 -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>الصورة والرسالة</h2>
                        <p>
                            ولأنّ الصوت لا يكتمل إلّا بها، لم تغِب الصورة أيضًا عن تصوُّرنا. ومنها أتى شعارُ الموقع: «كُن في
                            الصورة»؛ مختصِرًا مهمّتنا البسيطة والمعقّدة في آن: محاولة وضْع الجمهور في صورة ما يحدث محلّيًّا
                            وإقليميًّا ودوليًّا، مِن خلال محتوًى إعلامي متوازن ورصين.
                        </p>
                        <p>
                            نتوخّى الجودة في المحتوى والشكل، ونتقصّى الحقيقة، وننقل الأصوات والآراء والمواقف، على اختلافها،
                            بأمانة
                            وموضوعية.
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="about-divider">

                <!-- About Section 4 -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>الرؤية والحرية</h2>
                        <p>
                            اخترنا شهر نوفمبر، ذكرى اندلاع الثورة الجزائرية، تاريخًا لانطلاق «أصوات جزائرية»، استلهامًا
                            للثورة
                            وتضحياتها وقيَمها في منطقة دفعت وتدفع أثمانًا باهظة في سبيل تحرُّرها، من الجزائر إلى فلسطين،
                            وأكبرُ تلك
                            القيَم هي الحرّية التي نُدرك ألّا صحافة من دونها.
                        </p>
                        <p>
                            وقد كان قصدُنا أن نقول إنّ موقعنا وموقعكم يطمح لأن يكون فضاءً جامعًا لأصوات تحرُّرية لا عدد لها،
                            تلتقي
                            في مواجهة مقولات الهيمنة الاستعمارية ونقض أدواتها المعرفية ومغالطاتها.
                        </p>
                        <p>
                            وإنّنا نصبو لنتشارك مع بقية شعوب العالم مساحة حرّة غير محكومة بالهيمنة والمركزيات والخدائع
                            والاستغلال.
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="about-divider">

                <!-- About Section 5 -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>رأس المال</h2>
                        <p>
                            أمّا رأس المال الذي ننطلق منه؛ فهو ثقتكم، كتّابًا وقرّاء وشركاء ومؤمنين بأنّ الحرّية وحدها تمنح
                            الأصوات
                            معنًى.
                        </p>
                        <p class="semibold">
                            لتُسمَع وتُدرَك وتُلمَس، لتُكتَب وتُقرأ وتُرى.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="web">
            @include('user.components.footer')
        </div>

        <div class="mobile">
            @include('user.mobile.footer')
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var greyBar = document.querySelector('.grey-bar');
                var mobileNavbar = document.getElementById('mobileNavbar');
                var mobileFooter = document.getElementById('mobileFooter');

                if (!greyBar || !mobileNavbar || !mobileFooter) return;

                var obs = new IntersectionObserver(function(entries) {
                    entries.forEach(function(e) {
                        if (e.isIntersecting && e.intersectionRatio > 0.15) {
                            mobileNavbar.classList.add('navbar-hidden');
                            greyBar.classList.add('bar-hidden');
                        } else {
                            mobileNavbar.classList.remove('navbar-hidden');
                            greyBar.classList.remove('bar-hidden');
                        }
                    });
                }, {
                    threshold: [0, 0.15, 0.5, 1]
                });

                obs.observe(mobileFooter);
            });
        </script>

    @endsection
