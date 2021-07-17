const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .extract(['vue', 'bootstrap-vue', 'vuex', 'vue-router', 'axios'])
    .vue()
    .sourceMaps()
    .version()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

mix.alias({
    vue$: path.join(__dirname, 'node_modules/vue/dist/vue.js')
});

mix.options({ uglify: {} });