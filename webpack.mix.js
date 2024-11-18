// webpack.mix.js

const mix = require('laravel-mix');

// Basic setup to compile JavaScript and SCSS/CSS files
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles('resources/css/admin.css', 'public/css/admin.css') // Compiles admin.css if it exists
   .sourceMaps();
