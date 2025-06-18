// webpack.mix.js

let mix = require('laravel-mix');

mix.js('./src/js/app.js', 'dist').setPublicPath('public/');