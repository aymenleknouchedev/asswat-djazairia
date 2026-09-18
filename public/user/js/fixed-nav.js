// === Subnav toggle ===
const siteFirstNavLink = document.getElementById('site-show-subnav');
const siteSubnav = document.getElementById('site-subnav');
const siteMainNav = document.getElementById('site-main-nav');
const siteSecondaryNav = document.getElementById('site-secondary-nav');
const newsFeatureGrid = document.getElementById('news-feature-grid');

let isHoveringLink = false;
let isHoveringSubnav = false;
let hideTimeout = null;

// Track if the link is active on page load (from Blade template)
const isLatestNewsPageActive = siteFirstNavLink.getAttribute('class').includes('active');

siteFirstNavLink.addEventListener('mouseenter', () => {
    isHoveringLink = true;
    clearTimeout(hideTimeout);
    showSubnav();
});

siteFirstNavLink.addEventListener('mouseleave', () => {
    isHoveringLink = false;
    startHideTimeout();
});

siteSubnav.addEventListener('mouseenter', () => {
    isHoveringSubnav = true;
    clearTimeout(hideTimeout);
});

siteSubnav.addEventListener('mouseleave', () => {
    isHoveringSubnav = false;
    startHideTimeout();
});

function showSubnav() {
    siteSubnav.style.display = 'flex'; // Flex لو التصميم يحتاج ذلك
    siteFirstNavLink.classList.add('active'); // أضف الكلاس عند الفتح
    siteMainNav.classList.add('subnav-open'); // يخفي خط أسفل الشريط
}

function startHideTimeout() {
    hideTimeout = setTimeout(() => {
        if (!isHoveringLink && !isHoveringSubnav) {
            siteSubnav.style.display = 'none';
            siteMainNav.classList.remove('subnav-open');
            // Only keep active if it was active from the route (page load)
            if (!isLatestNewsPageActive) {
                siteFirstNavLink.classList.remove('active');
            }
        }
    }, 200); // تأخير 200ms لتفادي اختفاء مفاجئ
}

// === Close breaking news ===
const siteCloseBreaking = document.querySelector('.site-close-breaking');
const siteBreakingNews = document.getElementById('site-breaking-news');

siteCloseBreaking.addEventListener('click', () => {
    siteBreakingNews.style.display = 'none';
    document.body.classList.add('site-breaking-closed');
});

// === Scroll behavior ===
let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
    const currentScrollY = window.scrollY;

    if (currentScrollY === 0) {
        siteBreakingNews.classList.remove('fixed-bottom');
        triggerFade(siteBreakingNews);
    } else if (currentScrollY > lastScrollY) {
        if (!siteBreakingNews.classList.contains('fixed-bottom')) {
            siteBreakingNews.classList.add('fixed-bottom');
            triggerFade(siteBreakingNews);
        }
    }

    lastScrollY = currentScrollY;
});

function triggerFade(element) {
    element.classList.remove('fade-in');
    void element.offsetWidth;
    element.classList.add('fade-in');
}

// === Search toggle ===
const searchInput = document.querySelector('.site-search-input');
const searchButton = document.querySelector('.search-icon');

searchButton.addEventListener('click', (e) => {
    e.stopPropagation(); // يمنع تفعيل document click

    if (searchInput.classList.contains('active')) {
        // If input is visible and has text, let the form submit
        if (searchInput.value.trim().length > 0) {
            return; // allow default submit
        }
        // If empty, just hide it
        e.preventDefault();
        searchInput.classList.remove('active');
        searchInput.blur();
    } else {
        // Show the input and focus it, don't submit
        e.preventDefault();
        searchInput.classList.add('active');
        searchInput.focus();
    }
});

document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchButton.contains(e.target)) {
        searchInput.classList.remove('active');
    }
});

// === Breaking news typing ===
const element = document.getElementById('site-breaking-text');
const texts = [];
let lastFetchedData = null; // cache last result
let textIndex = 0;
let charIndex = 0;
const speed = 20;

let typingTimeout = null;
let switchingTimeout = null;
let breakingNewsInterval = null;

function clearTypingTimers() {
    clearTimeout(typingTimeout);
    clearTimeout(switchingTimeout);
}

function typeWriter() {
    if (!texts.length) return;

    if (charIndex < texts[textIndex].length) {
        element.textContent += texts[textIndex].charAt(charIndex++);
        typingTimeout = setTimeout(typeWriter, speed);
    } else {
        // Wait 3 seconds before switching to next text
        switchingTimeout = setTimeout(() => {
            textIndex = (textIndex + 1) % texts.length;
            element.textContent = '';
            charIndex = 0;
            typeWriter();
        }, 3000);
    }
}

function startTyping() {
    clearTypingTimers();
    element.textContent = '';
    charIndex = 0;
    textIndex = 0;
    typeWriter();
}

function fetchBreakingNews() {
    fetch('/api/breaking-news')
        .then(response => response.json())
        .then(res => {
            const data = res.data || []; // ✅ use the new structure

            const dataString = JSON.stringify(data);
            if (dataString !== lastFetchedData) {
                lastFetchedData = dataString;

                texts.length = 0;
                texts.push(...data);

                if (data.length > 0) {
                    siteBreakingNews.style.display = 'flex';
                    document.body.classList.remove('site-breaking-closed');
                    startTyping();
                } else {
                    // ✅ Hide immediately when no data
                    clearTypingTimers();
                    element.textContent = '';
                    siteBreakingNews.style.display = 'none';
                }
            }
        })
        .catch(err => console.error('Failed to fetch breaking news:', err));
}

// Initial fetch
fetchBreakingNews();

// Fetch every 5 seconds (not 1s — safer for performance)
if (siteBreakingNews) {
    breakingNewsInterval = setInterval(fetchBreakingNews, 5000);

    siteCloseBreaking.addEventListener('click', () => {
        clearInterval(breakingNewsInterval);
        breakingNewsInterval = null;
        clearTypingTimers();
    });
}



// === Latest news typing ===
const element2 = document.getElementById('site-latest-text');
const newsData = []; // Store objects with title and shortlink
let textIndex2 = 0;
let charIndex2 = 0;
const speed2 = 20;
let currentDisplayedNews = null; // Track the currently displayed news for click handling

// Add cursor pointer style
element2.style.cursor = 'pointer';

function typeWriter2() {
    if (charIndex2 < newsData[textIndex2].title.length) {
        element2.textContent += newsData[textIndex2].title.charAt(charIndex2++);
        setTimeout(typeWriter2, speed2);
    } else {
        // Wait 3 seconds, then clear and move to next text
        setTimeout(() => {
            element2.textContent = '';
            charIndex2 = 0;
            textIndex2 = (textIndex2 + 1) % newsData.length;
            currentDisplayedNews = newsData[textIndex2]; // Update tracking before typing starts
            typeWriter2();
        }, 3000);
    }
}

// Add click event to latest news text
element2.addEventListener('click', () => {
    // Use the tracked current news to ensure correct redirection
    if (currentDisplayedNews && currentDisplayedNews.shortlink) {
        window.location.href = `/article/${currentDisplayedNews.shortlink}`;
    }
});

fetch('/api/latest-news')
    .then(response => response.json())
    .then(data2 => {
        if (Array.isArray(data2) && data2.length > 0) {
            newsData.push(...data2);
            currentDisplayedNews = newsData[0]; // Initialize with first news item
            typeWriter2(); // ✅ Start typing after data loads
        }
    })
    .catch(err => console.error('Failed to fetch texts:', err));
