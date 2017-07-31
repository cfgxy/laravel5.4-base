const { mix, config: mixConfig } = require('laravel-mix');
const { glob } = require("glob");
const fs = require('fs');
const path = require('path');
const { _ } = require('lodash');


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

mix.sass('resources/assets/sass/app.scss', 'public/css');

//Mix vue app js
glob.sync('app/Features/*/vue/app.js').forEach(function (file) {
    let module = path.basename(path.dirname(path.dirname(file)));
    module = _.snakeCase(module).replace(/_/g, '-');
    mix.js(file, `public/js/${module}/app.js`);

    glob.sync(path.dirname(file) + '/pages/*.js').forEach(function (file) {
        mix.js(file, `public/js/${module}/`);
    });
});

//Mix module css
glob.sync('app/Features/*/resources/assets/sass/*.s[ac]ss').forEach(function (file) {
    let fileName = path.basename(file);
    if (fileName.indexOf('_') === 0) {
        return;
    }
    let module = path.basename(path.dirname(path.dirname(path.dirname(path.dirname(file)))));
    module = _.snakeCase(module).replace(/_/g, '-');

    const extname = path.extname(fileName).substring(1);
    const pubpath = `public/css/${module}/` + path.basename(fileName, '.' + extname) + '.css';
    mix.sass(file, pubpath);
});

//Mix module js
glob.sync('app/Features/*/resources/assets/js/*.js').forEach(function (file) {
    let fileName = path.basename(file);
    if (fileName.indexOf('_') === 0) {
        return;
    }
    let module = path.basename(path.dirname(path.dirname(path.dirname(path.dirname(file)))));
    module = _.snakeCase(module).replace(/_/g, '-');

    const pubpath = `public/js/${module}/${fileName}`;
    mix.js(file, pubpath);
});
