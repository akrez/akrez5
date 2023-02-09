const mix = require("laravel-mix");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath("public/dist/");
mix.setResourceRoot('../');

mix.js("resources/js/app.js", "js")
    .autoload({
        jquery: ["$", "window.jQuery", "jQuery"],
    })
    .sass("resources/sass/app.scss", "css")
    .sourceMaps();
