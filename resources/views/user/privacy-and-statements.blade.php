@extends('layouts.index')

@section('title', 'سياسة الخصوصية والبيان التحريري | أصوات')

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

        .about-section h3 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #000000;
            margin-top: 2rem;
            margin-bottom: 1rem;
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

        .about-section ul {
            font-size: 1.125rem;
            color: #000000;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            margin-right: 2rem;
        }

        .about-section ul li {
            margin-bottom: 0.75rem;
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

            .about-section h3 {
                font-size: 1.125rem;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
                line-height: 1.3;
            }

            .about-section p {
                font-size: 0.95rem;
                line-height: 1.7;
                margin-bottom: 1rem;
                text-align: right;
            }

            .about-section ul {
                font-size: 0.95rem;
                line-height: 1.7;
                margin-bottom: 1rem;
                margin-right: 1.5rem;
            }

            .about-section ul li {
                margin-bottom: 0.5rem;
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

            .about-section h3 {
                font-size: 1rem;
                margin-top: 1.25rem;
                margin-bottom: 0.65rem;
            }

            .about-section p {
                font-size: 0.9rem;
                line-height: 1.65;
                margin-bottom: 0.85rem;
            }

            .about-section ul {
                font-size: 0.9rem;
                line-height: 1.65;
                margin-bottom: 0.85rem;
                margin-right: 1rem;
            }

            .about-section ul li {
                margin-bottom: 0.4rem;
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
                <p>سياسة الخصوصية والبيان التحريري</p>
            </div>

            <!-- Privacy Policy Section -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>سياسة الخصوصية</h2>
                    <p>
                        يولي موقع «أصوات جزائرية» أهميةً قصوى لخصوصية مستخدميه واحترام بياناتهم الشخصية، ويلتزم بحمايتها وعدم إساءة استخدامها، وفقًا لأعلى المعايير المهنية والأخلاقية.
                    </p>

                    <h3>1. جمع المعلومات</h3>
                    <p>
                        لا يفرض الموقع على زوّاره تقديم أيّ معلومات شخصية للتصفّح. قد نقوم بجمع معلومات محدودة وغير حسّاسة بشكل تلقائي، مثل:
                    </p>
                    <ul>
                        <li>نوع المتصفّح والجهاز</li>
                        <li>الصفحات التي يتمّ تصفّحها</li>
                        <li>مدة الزيارة</li>
                    </ul>
                    <p>
                        وذلك لأغراض إحصائية وتحسين تجربة المستخدم فقط.
                    </p>

                    <h3>2. المعلومات التي يقدّمها المستخدم طوعًا</h3>
                    <p>
                        في حال تواصل المستخدم معنا عبر نماذج الاتصال، أو شارك بمحتوى، أو اشترك في النشرات البريدية (إن وُجدت)، فإنّ المعلومات المقدَّمة (كالاسم أو البريد الإلكتروني) تُستخدم حصريًا لغرض التواصل أو النشر، ولا تُشارك مع أيّ طرف ثالث دون موافقة صريحة من صاحبها.
                    </p>

                    <h3>3. ملفات تعريف الارتباط (Cookies)</h3>
                    <p>
                        قد يستخدم الموقع ملفات تعريف الارتباط لتحسين الأداء وتجربة التصفّح، دون تتبّع شخصي أو إعلاني، ويمكن للمستخدم تعطيلها من إعدادات المتصفّح.
                    </p>

                    <h3>4. حماية البيانات</h3>
                    <p>
                        نتّخذ الإجراءات التقنية والتنظيمية اللازمة لحماية البيانات من الوصول غير المصرّح به أو الاستخدام أو التعديل أو الإتلاف.
                    </p>

                    <h3>5. الروابط الخارجية</h3>
                    <p>
                        قد يحتوي الموقع على روابط لمواقع خارجية. «أصوات جزائرية» غير مسؤول عن سياسات الخصوصية أو محتوى تلك المواقع.
                    </p>

                    <h3>6. التعديلات</h3>
                    <p>
                        يحتفظ الموقع بحقّ تحديث سياسة الخصوصية عند الحاجة، على أن تُنشر التعديلات بوضوح على هذه الصفحة.
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <hr class="about-divider">

            <!-- Editorial Statement Section -->
            <div class="about-section">
                <div class="about-section-content">
                    <h2>البيان التحريري</h2>
                    <p>
                        «أصوات جزائرية» منصّة إعلامية مستقلة، تنطلق من الإيمان بأنّ الصحافة فعل معرفة ومسؤولية أخلاقية، وأنّ الصوت لا قيمة له إن لم يكن حرًّا، صادقاً، ومُدرَكًا.
                    </p>

                    <h3>1. الاستقلالية</h3>
                    <p>
                        يلتزم الموقع باستقلاله التحريري الكامل، ولا يخضع لأيّ جهة سياسية أو اقتصادية أو أيديولوجية. ما يُنشر فيه يعبّر عن رؤية تحريرية قائمة على الحرّية والنزاهة، لا على الإملاء أو الوصاية.
                    </p>

                    <h3>2. المهنية والمصداقية</h3>
                    <p>
                        نعتمد معايير الصحافة المهنية في:
                    </p>
                    <ul>
                        <li>التحقّق من المعلومات</li>
                        <li>تعدّد المصادر</li>
                        <li>الدقّة في الصياغة</li>
                        <li>الفصل بين الخبر والرأي</li>
                    </ul>
                    <p>
                        ونحرص على تصحيح أيّ خطأ مهني فور التثبّت منه.
                    </p>

                    <h3>3. احترام المتلقّي</h3>
                    <p>
                        نخاطب قارئًا واعيًا ونبيهاً، ونرفض التبسيط المُخلّ، أو الإثارة الفارغة، أو التلاعب بالعواطف. نؤمن بأنّ احترام عقل القارئ شرطٌ أساسي لصحافة جديرة بالثقة.
                    </p>

                    <h3>4. تعدّد الأصوات</h3>
                    <p>
                        يفتح «أصوات جزائرية» فضاءه لآراء ومواقف متعدّدة، في إطار احترام القيم الإنسانية، ورفض خطاب الكراهية، والتمييز، والتحريض على العنف.
                    </p>

                    <h3>5. الذاكرة والإنسان</h3>
                    <p>
                        نولي أهمية خاصة للقصص الإنسانية، ويوميات الناس، والذاكرة السياسية والاجتماعية والثقافية، بوصفها جزءًا لا يتجزّأ من فهم الحاضر وبناء المستقبل.
                    </p>

                    <h3>6. الحرّية كقيمة مركزية</h3>
                    <p>
                        تنطلق رؤيتنا من قناعة راسخة بأنّه لا صحافة بلا حرّية، وأنّ مواجهة الهيمنة المعرفية والاستعمارية، ونقد سردياتها، جزء من دور الإعلام الحر، في الجزائر وفي فضاء «جنوب العالم» عمومًا.
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
                    <p>سياسة الخصوصية والبيان التحريري</p>
                </div>

                <!-- Privacy Policy Section -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>سياسة الخصوصية</h2>
                        <p>
                            يولي موقع «أصوات جزائرية» أهميةً قصوى لخصوصية مستخدميه واحترام بياناتهم الشخصية، ويلتزم بحمايتها وعدم إساءة استخدامها، وفقًا لأعلى المعايير المهنية والأخلاقية.
                        </p>

                        <h3>1. جمع المعلومات</h3>
                        <p>
                            لا يفرض الموقع على زوّاره تقديم أيّ معلومات شخصية للتصفّح. قد نقوم بجمع معلومات محدودة وغير حسّاسة بشكل تلقائي، مثل:
                        </p>
                        <ul>
                            <li>نوع المتصفّح والجهاز</li>
                            <li>الصفحات التي يتمّ تصفّحها</li>
                            <li>مدة الزيارة</li>
                        </ul>
                        <p>
                            وذلك لأغراض إحصائية وتحسين تجربة المستخدم فقط.
                        </p>

                        <h3>2. المعلومات التي يقدّمها المستخدم طوعًا</h3>
                        <p>
                            في حال تواصل المستخدم معنا عبر نماذج الاتصال، أو شارك بمحتوى، أو اشترك في النشرات البريدية (إن وُجدت)، فإنّ المعلومات المقدَّمة (كالاسم أو البريد الإلكتروني) تُستخدم حصريًا لغرض التواصل أو النشر، ولا تُشارك مع أيّ طرف ثالث دون موافقة صريحة من صاحبها.
                        </p>

                        <h3>3. ملفات تعريف الارتباط (Cookies)</h3>
                        <p>
                            قد يستخدم الموقع ملفات تعريف الارتباط لتحسين الأداء وتجربة التصفّح، دون تتبّع شخصي أو إعلاني، ويمكن للمستخدم تعطيلها من إعدادات المتصفّح.
                        </p>

                        <h3>4. حماية البيانات</h3>
                        <p>
                            نتّخذ الإجراءات التقنية والتنظيمية اللازمة لحماية البيانات من الوصول غير المصرّح به أو الاستخدام أو التعديل أو الإتلاف.
                        </p>

                        <h3>5. الروابط الخارجية</h3>
                        <p>
                            قد يحتوي الموقع على روابط لمواقع خارجية. «أصوات جزائرية» غير مسؤول عن سياسات الخصوصية أو محتوى تلك المواقع.
                        </p>

                        <h3>6. التعديلات</h3>
                        <p>
                            يحتفظ الموقع بحقّ تحديث سياسة الخصوصية عند الحاجة، على أن تُنشر التعديلات بوضوح على هذه الصفحة.
                        </p>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="about-divider">

                <!-- Editorial Statement Section -->
                <div class="about-section">
                    <div class="about-section-content">
                        <h2>البيان التحريري</h2>
                        <p>
                            «أصوات جزائرية» منصّة إعلامية مستقلة، تنطلق من الإيمان بأنّ الصحافة فعل معرفة ومسؤولية أخلاقية، وأنّ الصوت لا قيمة له إن لم يكن حرًّا، صادقاً، ومُدرَكًا.
                        </p>

                        <h3>1. الاستقلالية</h3>
                        <p>
                            يلتزم الموقع باستقلاله التحريري الكامل، ولا يخضع لأيّ جهة سياسية أو اقتصادية أو أيديولوجية. ما يُنشر فيه يعبّر عن رؤية تحريرية قائمة على الحرّية والنزاهة، لا على الإملاء أو الوصاية.
                        </p>

                        <h3>2. المهنية والمصداقية</h3>
                        <p>
                            نعتمد معايير الصحافة المهنية في:
                        </p>
                        <ul>
                            <li>التحقّق من المعلومات</li>
                            <li>تعدّد المصادر</li>
                            <li>الدقّة في الصياغة</li>
                            <li>الفصل بين الخبر والرأي</li>
                        </ul>
                        <p>
                            ونحرص على تصحيح أيّ خطأ مهني فور التثبّت منه.
                        </p>

                        <h3>3. احترام المتلقّي</h3>
                        <p>
                            نخاطب قارئًا واعيًا ونبيهاً، ونرفض التبسيط المُخلّ، أو الإثارة الفارغة، أو التلاعب بالعواطف. نؤمن بأنّ احترام عقل القارئ شرطٌ أساسي لصحافة جديرة بالثقة.
                        </p>

                        <h3>4. تعدّد الأصوات</h3>
                        <p>
                            يفتح «أصوات جزائرية» فضاءه لآراء ومواقف متعدّدة، في إطار احترام القيم الإنسانية، ورفض خطاب الكراهية، والتمييز، والتحريض على العنف.
                        </p>

                        <h3>5. الذاكرة والإنسان</h3>
                        <p>
                            نولي أهمية خاصة للقصص الإنسانية، ويوميات الناس، والذاكرة السياسية والاجتماعية والثقافية، بوصفها جزءًا لا يتجزّأ من فهم الحاضر وبناء المستقبل.
                        </p>

                        <h3>6. الحرّية كقيمة مركزية</h3>
                        <p>
                            تنطلق رؤيتنا من قناعة راسخة بأنّه لا صحافة بلا حرّية، وأنّ مواجهة الهيمنة المعرفية والاستعمارية، ونقد سردياتها، جزء من دور الإعلام الحر، في الجزائر وفي فضاء «جنوب العالم» عمومًا.
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
