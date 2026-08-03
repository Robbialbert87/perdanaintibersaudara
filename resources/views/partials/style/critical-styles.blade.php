<style id="critical-css">
:root {
    --default-color: #314862;
    --background-color: #ffffff;
    --heading-color: #13447f;
    --surface-color: #ffffff;
    --accent-color: #065cc2;
    --contrast-color: #ffffff;
    --default-font: "Roboto", system-ui, -apple-system, "Segoe UI", Helvetica, Arial, sans-serif;
    --heading-font: "Quicksand", "Segoe UI", system-ui, sans-serif;
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    scroll-behavior: smooth;
}

*,
*::before,
*::after {
    box-sizing: border-box;
}

body {
    margin: 0;
    color: var(--default-color);
    background-color: var(--background-color);
    font-family: var(--default-font);
    -webkit-text-size-adjust: 100%;
}

h1, h2, h3, h4, h5, h6 {
    margin: 0 0 .5rem 0;
    color: var(--heading-color);
    font-family: var(--heading-font);
    font-weight: 700;
}

p { margin-top: 0; }

a { color: var(--accent-color); text-decoration: none; }

img { vertical-align: middle; }

.img-fluid { max-width: 100%; height: auto; }

.container, .container-fluid, .container-xl {
    width: 100%;
    padding-right: calc(var(--bs-gutter-x) * .5);
    padding-left: calc(var(--bs-gutter-x) * .5);
    margin-right: auto;
    margin-left: auto;
}

@media (min-width: 576px) { .container { max-width: 540px; } }
@media (min-width: 768px) { .container { max-width: 720px; } }
@media (min-width: 992px) { .container { max-width: 960px; } }
@media (min-width: 1200px) { .container, .container-xl { max-width: 1140px; } }
@media (min-width: 1400px) { .container, .container-xl { max-width: 1320px; } }

.row {
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(-1 * var(--bs-gutter-y));
    margin-right: calc(-.5 * var(--bs-gutter-x));
    margin-left: calc(-.5 * var(--bs-gutter-x));
}

.row > * {
    flex-shrink: 0;
    width: 100%;
    max-width: 100%;
    padding-right: calc(var(--bs-gutter-x) * .5);
    padding-left: calc(var(--bs-gutter-x) * .5);
    margin-top: var(--bs-gutter-y);
}

.g-0 { --bs-gutter-x: 0; --bs-gutter-y: 0; }
.g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
.gy-4 { --bs-gutter-y: 1.5rem; }
.gy-5 { --bs-gutter-y: 3rem; }

.col-lg-6 { flex: 0 0 auto; width: 50%; }

@media (max-width: 991.98px) {
    .col-lg-6 { width: 100%; }
}

.d-flex { display: flex !important; }
.d-none { display: none !important; }
.d-xl-flex { display: none !important; }
.d-xl-none { display: none !important; }

@media (min-width: 1200px) {
    .d-xl-flex { display: flex !important; }
    .d-xl-none { display: none !important; }
}

.flex-column { flex-direction: column !important; }
.align-items-center { align-items: center !important; }
.justify-content-between { justify-content: space-between !important; }
.text-center { text-align: center !important; }
.position-relative { position: relative !important; }
.position-absolute { position: absolute !important; }
.top-0 { top: 0 !important; }
.start-0 { left: 0 !important; }
.w-100 { width: 100% !important; }
.h-100 { height: 100% !important; }
.overflow-hidden { overflow: hidden !important; }
.rounded-4 { border-radius: .75rem !important; }
.mt-5 { margin-top: 3rem !important; }
.mb-1 { margin-bottom: .25rem !important; }
.mb-2 { margin-bottom: .5rem !important; }
.mb-4 { margin-bottom: 1.5rem !important; }
.p-3 { padding: 1rem !important; }

@media (min-width: 992px) {
    .mt-lg-0 { margin-top: 0 !important; }
}

/* Header */
.header {
    --background-color: rgba(255, 255, 255, 0);
    color: #314862;
    background-color: var(--background-color);
    padding: 18px 0;
    transition: all .5s;
    z-index: 997;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    align-items: center;
}

.header .header-container {
    background: #fff;
    border-radius: 50px;
    padding: 10px 25px 10px 30px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, .08);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header .logo { line-height: 1; display: flex; align-items: center; }
.header .logo img { max-height: 40px; width: auto; margin-right: 8px; }

.header .navmenu { display: flex; align-items: center; }

.header .btn-getstarted {
    color: #fff;
    background: #00a5e5;
    font-size: 15px;
    padding: 10px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
    font-family: var(--default-font);
}

body:not(.index-page) { padding-top: 96px; }

/* Sections */
.section {
    color: var(--default-color);
    background-color: var(--background-color);
    padding: 60px 0;
}

/* Hero */
.hero {
    padding: 120px 0;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    align-items: center;
    color: var(--default-color);
}

.index-page .hero { padding-top: 120px !important; }

.hero .container { position: relative; z-index: 2; }

.hero .content-wrapper { max-width: 600px; }

.hero .hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero .lead {
    font-size: 1.2rem;
    color: #314862;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.hero .hero-stats { display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 3rem; }

.hero .hero-actions { display: flex; gap: 1rem; margin-bottom: 3rem; }

@media (max-width: 767.98px) {
    .hero .hero-title { font-size: 1.5rem; }
    .hero-content .lead { font-size: .9rem !important; }
    .hero { padding-top: 80px !important; min-height: 0; }
}

/* Buttons */
.btn {
    display: inline-block;
    font-weight: 600;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    border: 2px solid transparent;
    transition: all .3s ease;
    font-family: var(--default-font);
}

.btn.btn-primary { background-color: #065cc2; border-color: #065cc2; color: #fff; }
.btn.btn-outline { background-color: transparent; border-color: #065cc2; color: #065cc2; }

/* Swiper basics (fade hero) */
.swiper { position: relative; overflow: hidden; list-style: none; padding: 0; z-index: 1; }
.swiper-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: flex;
    transition-property: transform;
    box-sizing: content-box;
}
.swiper-slide { flex-shrink: 0; width: 100%; height: 100%; position: relative; transition-property: transform; }
.hero-services-swiper { aspect-ratio: 4 / 3; }
.hero-services-swiper .swiper-slide { height: 100%; }

@media (max-width: 767.98px) {
    .hero-services-swiper { aspect-ratio: 4 / 3; }
}
</style>
