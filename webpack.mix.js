const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js') // Menggabungkan dan mengompres file JavaScript
   .sass('resources/sass/app.scss', 'public/css') // Menggabungkan dan mengompres file Sass/SCSS
   .version(); // Menambahkan hash ke nama file untuk cache-busting
