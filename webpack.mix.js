const mix = require('laravel-mix');

mix.js([
    'assets/js/global/dark-mode.js',
    'assets/js/global/load-at-top.js',
    'assets/js/global/smooth-scrolling.js',
    'assets/js/global/remove-anchor-from-url.js',
    'assets/js/global/form-loading.js',
], 'js/global.js');

mix.js([
    'assets/js/header/header.js',
    'assets/js/header/mobile-menu.js',
    'assets/js/header/scrollspy.js',
    'assets/js/header/dropdown.js',
], 'js/header.js');

mix.js([
    'assets/js/footer/footer.js',
], 'js/footer.js');

mix.js([
    'assets/js/spline/spline-viewer.js',
    'assets/js/homepage/hero.js',
    'assets/js/homepage/marquee.js',
    'assets/js/homepage/why.js',
    'assets/js/homepage/services.js',
    'assets/js/homepage/work.js',
    'assets/js/homepage/our-work.js',
    'assets/js/homepage/process.js',
    'assets/js/homepage/testimonials.js',
    'assets/js/homepage/reveal.js',
    'assets/js/homepage/counter.js',
    'assets/js/homepage/contact.js',
], 'js/homepage.js');

mix.js([
    'assets/js/blog/reveal.js',
    'assets/js/blog/filter.js',
    'assets/js/blog/toc.js',
], 'js/archive-blog.js');

mix.js([
    'assets/js/blog/reveal.js',
    'assets/js/blog/filter.js',
    'assets/js/blog/toc.js',
], 'js/archive-author.js');


mix.js([
    'assets/js/blog/reveal.js',
    'assets/js/blog/filter.js',
    'assets/js/blog/toc.js',
], 'js/single-blog.js');

mix.js([
    'assets/js/contact/reveal.js',
    'assets/js/contact/next-steps.js',
    'assets/js/contact/form-progress.js',
], 'js/contact.js');

// Multi-step enquiry form (Gravity Form, cssClass `enquiry-form`). Own bundle
// so future placements only need the enqueue condition extending.
mix.js([
    'assets/js/enquiry-form/steps.js',
], 'js/enquiry-form.js');

mix.js([
    'assets/js/about/reveal.js',
    'assets/js/about/founders.js',
    'assets/js/about/story.js',
    'assets/js/about/values.js',
    'assets/js/about/how.js',
    // Shared homepage modules (bind by element id; the blog bundles set the
    // same multi-bundle precedent).
    'assets/js/homepage/testimonials.js',
    'assets/js/homepage/marquee.js',
], 'js/about.js');

mix.js([
    'assets/js/services-hub/reveal.js',
    'assets/js/services-hub/grid.js',
    // Shared homepage modules: process.js binds .process .process-steps,
    // testimonials.js/marquee.js bind by element id (the about.js precedent).
    'assets/js/homepage/process.js',
    'assets/js/homepage/testimonials.js',
    'assets/js/homepage/marquee.js',
], 'js/services-hub.js');

mix.js([
    'assets/js/service/reveal.js',
    'assets/js/service/deliverables.js',
    'assets/js/service/journey.js',
    'assets/js/service/faq.js',
    // Shared modules: grid.js staggers the related-services cards
    // (.services-grid), counter.js binds .results .stat-number, our-work.js
    // drives the recent-work wheel (#work-wheel).
    'assets/js/services-hub/grid.js',
    'assets/js/homepage/counter.js',
    'assets/js/homepage/our-work.js',
], 'js/service.js');

mix.js([
    'assets/js/project/reveal.js',
    'assets/js/project/filter.js',
    // Shared module: our-work.js drives the related-work wheel (#work-wheel)
    // on single project pages; it no-ops on the archive.
    'assets/js/homepage/our-work.js',
], 'js/project.js');

mix.js([
    'assets/js/case-study/reveal.js',
    'assets/js/case-study/filter.js',
    // Shared module: counter.js binds .results .stat-number for the single
    // pages' results band; it no-ops on the archive. No wheel and no carousel
    // on these pages.
    'assets/js/homepage/counter.js',
], 'js/case-study.js');

mix.js([
    'assets/js/free-website/reveal.js',
    'assets/js/free-website/how-scroll.js',
    'assets/js/free-website/ledger.js',
    'assets/js/free-website/faq.js',
    'assets/js/free-website/sticky-cta.js',
    // Shared module: the testimonial spotlight carousel binds
    // #testimonial-splide (the about/services-hub precedent).
    'assets/js/homepage/testimonials.js',
], 'js/free-website.js');

// Reusable Landing Page (flexible-content blocks). Modules query all instances
// of each block so blocks can repeat/reorder. Shared modules: the testimonial
// spotlight (#testimonial-splide) and the results counter (.results .stat-number).
mix.js([
    'assets/js/landing/reveal.js',
    'assets/js/landing/steps-scroll.js',
    'assets/js/landing/checklist.js',
    'assets/js/landing/faq.js',
    'assets/js/landing/sticky-cta.js',
    'assets/js/landing/logos.js',
    'assets/js/homepage/testimonials.js',
    'assets/js/homepage/counter.js',
], 'js/landing.js');

mix.sass('assets/css/app.scss', 'css/app.css')
    .options({
        processCssUrls: false
    });

mix.options({
    postCss: [
        require('autoprefixer')({
            overrideBrowserslist: ['last 3 versions'],
            cascade: false
        })
    ]
});

mix.setPublicPath('dist');
mix.sourceMaps();
mix.disableNotifications();
mix.version();

// Cache-safe lazy chunks. `mix.version()` only cache-busts the entry bundles
// (homepage.js?id=...); the code-split chunks under js/global/ reuse the same
// filename every build yet ship a 1-year cache header, so a CDN or browser can
// serve a stale chunk that no longer matches a freshly-deployed entry bundle
// (this silently dropped the homepage Spline statue to its poster). Content-
// hashing the chunk filename gives each build's chunks a unique URL, so a
// mismatch can never be served. `clean` prunes superseded hashes, scoped to
// js/global/ so entry bundles, CSS and the Mix manifest are left untouched.
mix.webpackConfig({
    output: {
        chunkFilename: 'js/global/[name].[contenthash:8].js',
        clean: {
            keep: (asset) => !asset.replace(/\\/g, '/').startsWith('js/global/'),
        },
    },
});