const mix = require('laravel-mix');


// require('laravel-mix-bundle-analyzer');

// if (!mix.inProduction()) {
//     mix.bundleAnalyzer();
// }

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

const CompressionPlugin = require('compression-webpack-plugin');

mix.options({
    uglify: {
      uglifyOptions: {
        sourceMap: true,
        compress: {
            warnings: false,
            screw_ie8: true,
            conditionals: true,
            unused: true,
            comparisons: true,
            sequences: true,
            dead_code: true,
            evaluate: true,
            if_return: true,
            join_vars: true,
        },
        output: {
            comments: false
        },
      }
    }
  });

mix.webpackConfig({
    plugins: [
      new CompressionPlugin({
        filename: '[path].gz[query]',
        algorithm: 'gzip',
        test: /\.js$|\.css$|\.html$/,
        threshold: 10240,
        minRatio: 0.8,
      }),
    ],
}); 


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');


