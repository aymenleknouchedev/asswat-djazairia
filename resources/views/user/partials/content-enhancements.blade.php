{{-- ============================================================================
     Shared article-content enhancements (styles + fullscreen viewer + gallery
     slider + custom audio player + tables). Include this on any page that renders
     rich editor HTML inside a `.custom-article-content` wrapper.
     Mirrors the behaviour built inline in user/news.blade.php.
     ========================================================================= --}}
<style>
    /* ===== Base content typography ===== */
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
        /* Bottom margin deliberately much smaller than the top: a heading
           belongs to the text that follows it, not to the block above. */
        margin-top: 40px !important;
        margin-bottom: 16px !important;
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
        margin: 32px 0 12px !important;
    }

    .custom-article-content img {
        display: block;
        max-width: 100% !important;
        height: auto !important;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .custom-article-content hr {
        border: none;
        height: 1px;
        background-color: #cacaca;
        margin: 24px 0;
    }

    /* ===== Figures & captions ===== */
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

    /* ===== Video / embeds ===== */
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

    /* ===== Audio: native element is the hidden engine, custom UI replaces it ===== */
    .custom-article-content audio {
        display: none !important;
    }

    .custom-article-content figure.audio {
        margin: 25px 0;
        width: 100%;
    }

    .custom-article-content .aud-player {
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

    .custom-article-content figure.audio > .aud-player {
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

    .aud-player .aud-play:hover { background: #333; }
    .aud-player .aud-play:active { transform: scale(.94); }
    .aud-player .aud-play svg { display: block; }

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

    .custom-article-content .table-wrap > table { border: none; }

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

    /* ===== Content gallery slider ===== */
    .vvc-cgallery{position:relative;width:100%;max-width:100%;display:block;background:transparent;border:0;border-radius:0;overflow:hidden;margin:25px 0;color:#fff;font-family:inherit;direction:rtl;contain:layout style;box-sizing:border-box;}
    .vvc-cgallery *{box-sizing:border-box;}
    .custom-article-content .vvc-cgallery i[class*="fa-"],
    .vvc-cgallery i[class*="fa-"]{font-family:"Font Awesome 6 Free","Font Awesome 6 Brands","FontAwesome" !important;font-weight:900 !important;font-style:normal !important;line-height:1 !important;}
    .vvc-cgallery i.fa-spin{animation:vvc-cg-spin 1s linear infinite;}
    .vvc-cgallery .vvc-cgs-stage{position:relative !important;width:100% !important;aspect-ratio:16/10;background:#000;overflow:hidden;isolation:isolate;}
    @supports not (aspect-ratio:1/1){ .vvc-cgallery .vvc-cgs-stage{height:60vw;max-height:520px;} }
    .vvc-cgallery .vvc-cgs-track{position:absolute;inset:0;display:flex;width:100%;height:100%;transition:transform .45s cubic-bezier(.4,.0,.2,1);will-change:transform;}
    .custom-article-content .vvc-cgallery .vvc-cgs-slide,
    .vvc-cgallery .vvc-cgs-slide{position:relative !important;flex:0 0 100% !important;width:100% !important;height:100% !important;margin:0 !important;background:#000;display:block;}
    .custom-article-content .vvc-cgallery .vvc-cgs-img,
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

    /* ===== Fullscreen image viewer ===== */
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
        animation: ce-fadeIn 0.3s;
    }

    @keyframes ce-fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .fullscreen-image-modal.active { display: flex; }

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

    .fullscreen-image-close:hover { background: rgba(0, 0, 0, 0.45); }
    .fullscreen-image-close .material-symbols-outlined { font-size: 22px; }

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
    .fullscreen-image-next:hover { background: rgba(0, 0, 0, 0.8); }
    .fullscreen-image-prev .material-symbols-outlined,
    .fullscreen-image-next .material-symbols-outlined { font-size: 28px; }
    .fullscreen-image-prev { right: 20px; }
    .fullscreen-image-next { left: 20px; }
    .fullscreen-image-prev:disabled,
    .fullscreen-image-next:disabled { opacity: 0.1; cursor: not-allowed; pointer-events: none; }

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
</style>

{{-- ===== Fullscreen image viewer modal ===== --}}
<div id="fullscreenImageModal" class="fullscreen-image-modal">
    <div class="fullscreen-image-container">
        <button class="fullscreen-image-close" id="fullscreenImageClose" type="button" aria-label="إغلاق"><span class="material-symbols-outlined">close</span></button>
        <button class="fullscreen-image-prev" id="fullscreenImagePrev" type="button" aria-label="الصورة السابقة"><span class="material-symbols-outlined">chevron_right</span></button>
        <button class="fullscreen-image-next" id="fullscreenImageNext" type="button" aria-label="الصورة التالية"><span class="material-symbols-outlined">chevron_left</span></button>
        <img loading="lazy" decoding="async" id="fullscreenImageContent" src="" alt="صورة بحجم كامل">
        <div class="fullscreen-image-caption" id="fullscreenImageCaption"></div>
        <div class="fullscreen-image-counter" id="fullscreenImageCounter"></div>
    </div>
</div>

<script>
    (function () {
        // ---------------------------------------------------------------
        // Fullscreen viewer
        // ---------------------------------------------------------------
        const modal   = document.getElementById('fullscreenImageModal');
        if (!modal) return;
        const imgEl   = document.getElementById('fullscreenImageContent');
        const capEl   = document.getElementById('fullscreenImageCaption');
        const cntEl   = document.getElementById('fullscreenImageCounter');
        const closeEl = document.getElementById('fullscreenImageClose');
        const prevEl  = document.getElementById('fullscreenImagePrev');
        const nextEl  = document.getElementById('fullscreenImageNext');

        let images = [], index = 0;

        function render() {
            if (!images.length) return;
            const cur = images[index] || {};
            imgEl.src = cur.src || '';
            if (cur.caption && cur.caption.trim() !== '') {
                capEl.textContent = cur.caption.replace(/"([^"]*)"/g, '«$1»');
                capEl.style.display = 'block';
            } else {
                capEl.style.display = 'none';
            }
            cntEl.textContent = (index + 1) + ' / ' + images.length;
            prevEl.disabled = index === images.length - 1;
            nextEl.disabled = index === 0;
            const single = images.length <= 1;
            prevEl.style.display = single ? 'none' : 'flex';
            nextEl.style.display = single ? 'none' : 'flex';
            cntEl.style.display  = single ? 'none' : 'block';
        }

        function open(imgs, i) {
            if (!Array.isArray(imgs) || !imgs.length) return;
            images = imgs;
            index = Math.min(Math.max(i || 0, 0), imgs.length - 1);
            render();
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function close() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        // RTL: "next" moves to a lower index, "prev" to a higher one
        function goNext() { if (index > 0) { index--; render(); } }
        function goPrev() { if (index < images.length - 1) { index++; render(); } }

        closeEl.addEventListener('click', close);
        prevEl.addEventListener('click', goPrev);
        nextEl.addEventListener('click', goNext);
        document.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('active')) return;
            if (e.key === 'ArrowLeft') goNext();
            else if (e.key === 'ArrowRight') goPrev();
        });
        modal.querySelector('.fullscreen-image-container').addEventListener('click', (e) => e.stopPropagation());

        // Exposed so the gallery slider can open a gallery-scoped viewer
        window.openContentGallery = open;

        // ---------------------------------------------------------------
        // Content images (non-gallery): click to open the viewer
        // ---------------------------------------------------------------
        function initContentImages() {
            const list = [];
            document.querySelectorAll('.custom-article-content img').forEach((img) => {
                if (img.closest('.read-more-block')) return;
                if (img.closest('.vvc-content-gallery') || img.closest('.vvc-cgallery')) return;
                const figure = img.closest('figure');
                const caption = figure ? (figure.querySelector('figcaption')?.textContent?.trim() || '') : '';
                list.push({ src: img.src, caption: caption, el: img });
            });
            list.forEach((data, i) => {
                data.el.addEventListener('click', (e) => {
                    e.preventDefault();
                    open(list.map(d => ({ src: d.src, caption: d.caption })), i);
                });
            });
        }

        // ---------------------------------------------------------------
        // Custom audio players
        // ---------------------------------------------------------------
        function initAudioPlayers() {
            const audios = document.querySelectorAll('.custom-article-content audio');
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
                playBtn.addEventListener('click', () => { if (audio.paused) audio.play(); else audio.pause(); });

                const seek = (clientX) => {
                    const r = bar.getBoundingClientRect();
                    const ratio = Math.min(Math.max((clientX - r.left) / r.width, 0), 1);
                    if (audio.duration) audio.currentTime = ratio * audio.duration;
                };
                bar.addEventListener('click', (e) => seek(e.clientX));
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

        // ---------------------------------------------------------------
        // Tables: scroll wrapper + highlight bold cells
        // ---------------------------------------------------------------
        function enhanceTables() {
            document.querySelectorAll('.custom-article-content table').forEach((table) => {
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

        // ---------------------------------------------------------------
        // Gallery slider (builds .vvc-cgallery from .vvc-content-gallery blocks)
        // ---------------------------------------------------------------
        function parseItems(node) {
            const raw = node.getAttribute('data-vvc-gallery') || '';
            if (!raw) return [];
            const tries = [raw];
            if (raw.indexOf('%') !== -1) { try { tries.push(decodeURIComponent(raw)); } catch (_) {} }
            if (raw.indexOf('&') !== -1) {
                tries.push(raw.replace(/&quot;/g, '"').replace(/&#39;/g, "'").replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&'));
            }
            for (const s of tries) { try { const v = JSON.parse(s); if (Array.isArray(v)) return v; } catch (_) {} }
            return [];
        }
        const escAttr = (s) => String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

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

            const stageEl = wrap.querySelector('.vvc-cgs-stage');
            function applyNaturalRatio(img) {
                if (!stageEl || !img || !img.naturalWidth || !img.naturalHeight) return;
                stageEl.style.aspectRatio = img.naturalWidth + '/' + img.naturalHeight;
            }

            const track   = wrap.querySelector('.vvc-cgs-track');
            const slides  = Array.from(wrap.querySelectorAll('.vvc-cgs-slide'));
            const imgs    = slides.map(s => s.querySelector('.vvc-cgs-img'));
            const dots    = Array.from(wrap.querySelectorAll('.vvc-cgs-progress span'));
            const caption = wrap.querySelector('.vvc-cgs-caption');
            const source  = wrap.querySelector('.vvc-cgs-source');
            const playBtn = wrap.querySelector('.vvc-cgs-play');
            const toggle  = wrap.querySelector('.vvc-cgs-toggle');
            const stage   = wrap.querySelector('.vvc-cgs-stage');
            const total   = items.length;
            let idx = 0, autoTimer = null, playing = false;

            const fsImages = items.map(it => ({
                src: it.u || '',
                caption: (it.t && String(it.t).trim()) ? it.t : (it.a || '')
            }));

            imgs.forEach((img, i) => {
                const slide = slides[i];
                const done  = () => { img.classList.add('is-loaded'); slide.classList.remove('is-loading'); if (i === 0) applyNaturalRatio(img); };
                const fail  = () => { slide.classList.remove('is-loading'); slide.classList.add('is-failed'); };
                img.addEventListener('load',  done);
                img.addEventListener('error', fail);
                if (img.complete) { if (img.naturalWidth > 0) done(); else fail(); }
                img.style.cursor = 'zoom-in';
                img.addEventListener('click', () => open(fsImages, i));
            });

            function updateTrack() { track.style.transform = `translateX(${idx * 100}%)`; }

            const captionWrap = wrap.querySelector('.vvc-cgs-caption-wrap');
            function show(i) {
                idx = (i + total) % total;
                updateTrack();
                dots.forEach((el, k) => el.classList.toggle('is-active', k === idx));
                const cur = items[idx] || {};
                const hasTitle  = !!(cur.t && String(cur.t).trim());
                const hasSource = !!(cur.a && cur.a !== cur.t && String(cur.a).trim());
                caption.textContent = hasTitle ? cur.t : '';
                if (hasSource) { source.hidden = false; source.textContent = cur.a; }
                else { source.hidden = true; source.textContent = ''; }
                if (captionWrap) captionWrap.classList.toggle('is-empty', !hasTitle && !hasSource);
            }

            function play() {
                if (playing || total < 2) return;
                playing = true;
                playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                autoTimer = setInterval(() => show(idx + 1), 4000);
            }
            function pause() {
                playing = false;
                playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
            }

            wrap.querySelector('.vvc-cgs-prev').addEventListener('click', () => { pause(); show(idx + 1); });
            wrap.querySelector('.vvc-cgs-next').addEventListener('click', () => { pause(); show(idx - 1); });
            dots.forEach(d => d.addEventListener('click', () => { pause(); show(parseInt(d.dataset.idx, 10)); }));
            playBtn.addEventListener('click', () => (playing ? pause() : play()));
            toggle.addEventListener('click', () => wrap.classList.toggle('is-collapsed'));

            let touchX = null, dragOffset = 0;
            stage.addEventListener('touchstart', (e) => { touchX = e.touches[0].clientX; track.style.transition = 'none'; }, { passive: true });
            stage.addEventListener('touchmove', (e) => {
                if (touchX == null) return;
                dragOffset = e.touches[0].clientX - touchX;
                track.style.transform = `translateX(calc(${idx * 100}% + ${dragOffset}px))`;
            }, { passive: true });
            stage.addEventListener('touchend', () => {
                if (touchX == null) return;
                track.style.transition = '';
                if (Math.abs(dragOffset) > 50) { pause(); show(idx + (dragOffset < 0 ? 1 : -1)); }
                else updateTrack();
                touchX = null; dragOffset = 0;
            });

            wrap.tabIndex = 0;
            wrap.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft')  { pause(); show(idx + 1); }
                if (e.key === 'ArrowRight') { pause(); show(idx - 1); }
            });

            show(0);
        }

        function initGalleries() {
            document.querySelectorAll('.vvc-content-gallery[data-vvc-gallery]').forEach(buildSlider);
        }

        function initAll() {
            initGalleries();
            initAudioPlayers();
            enhanceTables();
            initContentImages();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAll);
        } else {
            initAll();
        }
    })();
</script>
