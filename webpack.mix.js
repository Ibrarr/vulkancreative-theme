const mix = require('laravel-mix');

mix.js([
    'assets/js/global/dark-mode.js',
    'assets/js/global/load-at-top.js',
    'assets/js/global/smooth-scrolling.js',
    'assets/js/global/remove-anchor-from-url.js',
], 'js/global.js');

mix.js([
    'assets/js/header/header.js',
    'assets/js/header/mobile-menu.js',
    'assets/js/header/scrollspy.js',
], 'js/header.js');

mix.js([
    'assets/js/footer/footer.js',
], 'js/footer.js');

mix.js([
    'assets/js/spline/spline-viewer.js',
    'assets/js/homepage/hero.js',
    'assets/js/homepage/marquee.js',
    'assets/js/homepage/why.js',
    'assets/js/homepage/story.js',
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
    'assets/js/archive-blog/loading.js',
], 'js/archive-blog.js');

mix.js([
    'assets/js/archive-author/loading.js',
], 'js/archive-author.js');


mix.js([
    'assets/js/single-blog/loading.js',
], 'js/single-blog.js');

mix.js([
    'assets/js/your-business/hero.js',
    'assets/js/your-business/logo-bar.js',
    'assets/js/your-business/problem.js',
    'assets/js/your-business/solution.js',
    'assets/js/your-business/outcomes.js',
    'assets/js/your-business/testimonials.js',
    'assets/js/your-business/cta.js',
], 'js/your-business.js');

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