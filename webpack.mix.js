const mix = require('laravel-mix');

require('laravel-mix-purgecss');

mix.disableNotifications()
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sourceMaps();

if (mix.inProduction()) {
  const options = {
    postCss: [
      require('postcss-discard-comments')({
        removeAll: true
      })
    ],
  };

  mix.options(options)
    .purgeCss()
    .version();
}
