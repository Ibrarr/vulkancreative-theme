const mix = require('laravel-mix');

mix.js([
    'assets/js/global/load-at-top.js',
    'assets/js/global/custom-cursor.js',
], 'js/global.js');

mix.js([
    'assets/js/header/header.js',
], 'js/header.js');

mix.js([
    'assets/js/homepage/hero.js',
    'assets/js/homepage/why.js',
    'assets/js/homepage/story.js',
    'assets/js/homepage/services.js',
], 'js/homepage.js');


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