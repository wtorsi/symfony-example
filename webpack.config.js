const Encore = require('@symfony/webpack-encore');
const WorkboxPlugin = require('workbox-webpack-plugin');


// main config
Encore.reset();
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/')
    .setManifestKeyPrefix('build/')
    .addEntry('page/page/index', './assets/entry/page/page/index.js')
    .addEntry('error/error/index', './assets/entry/error/error/index.js')
    .enableSingleRuntimeChunk()
    .splitEntryChunks()
    .addLoader({
        test: /\.(gif|svg|webp)$/i,
        loader: 'image-webpack-loader',
        options: {
            bypassOnDebug: false, //only minify during production
            plugins: [
                {removeTitle: true},
                {convertColors: {shorthex: false}},
                {convertPathData: false}
            ],
            mozjpeg: {
                progressive: true,
                quality: 75
            },
            // optipng.enabled: false will disable optipng
            optipng: {
                enabled: false,
            },
            pngquant: {
                quality: [0.65, 0.90],
                speed: 4
            },
            gifsicle: {
                interlaced: false,
            },
            webp: {
                quality: 60,
                method: 6
            }
        },
    })
    .copyFiles({
        from: './assets/img',
        to: 'img/[path][name].[ext]',
        pattern: /\.(png|jpg|jpeg|svg|webp|jp2)$/
    })
    .copyFiles({
        from: './assets/font',
        to: 'font/[path][name].[ext]',
    })
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((babelConfig) => {

    }, {
        useBuiltIns: 'usage',
        corejs: 3,
        includeNodeModules: ['bootstrap']
    })
    .addPlugin(new WorkboxPlugin.GenerateSW({
        swDest: "../sw.js",
        skipWaiting: true,
        clientsClaim: true,
        cleanupOutdatedCaches: true,
    }))
    .enableSassLoader()
    .autoProvidejQuery();


if (Encore.isProduction()) {
    Encore
        .enablePostCssLoader()
        .configureUrlLoader({
            fonts: {limit: 4096},
            images: {limit: 4096}
        })
        .setPublicPath('/build');
}


const _default = Encore.getWebpackConfig();
_default.name = '_default';

module.exports = [_default];
