const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

const paths = {
    'bulma': 'node_modules/bulma/',
};

elixir(mix => {

    mix.copy(paths.bulma+'sass/**', 'resources/assets/sass');

    mix.sass('app.scss')
       .webpack('app.js');
});
