const Encore = require('@symfony/webpack-encore');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('style', './assets/css/style.css')
    .addEntry('app', './assets/app.js')
    .addEntry('profile', './assets/js/profile.js')
    .addEntry('graph', './assets/js/graph.js')
    .addEntry('buggerMenu', './assets/js/buggerMenu.js')
    .addEntry('filter-mobile', './assets/js/filter-mobile.js')
    .addEntry('redirection', './assets/js/redirection.js')
    .addEntry('uploadFile', './assets/js/uploadFile.js')
    .addEntry('createAccountPassword', './assets/js/createAccountPassword.js')
    .addEntry('overlayCar', './assets/js/overlayCar.js')
    .addEntry('detailsRedirection', './assets/js/detailsRedirection.js')
    .addEntry('participationRedirection', './assets/js/participationRedirection.js')
    .addEntry('startCarpool', './assets/js/startCarpool.js')
    .addEntry("carpoolMark", "./assets/js/carpoolMark.js")

    // Enable splitting for better optimization
    .splitEntryChunks()

    // Enable Stimulus bridge for Symfony UX
    .enableStimulusBridge('./assets/controllers.json')

    // Enable single runtime chunk
    .enableSingleRuntimeChunk()


    .copyFiles({
    from: './assets/img', // Source folder
    to: 'img/[path][name].[hash:8].[ext]', // Destination folder
    })
    // Cleanup before build, enable notifications, and source maps in development
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())

    // Enable versioning (hash filenames) in production
    .enableVersioning(Encore.isProduction())

    // Babel configuration
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })
    
    // Enable processing of CSS files
    Encore.enablePostCssLoader();

    // Uncomment for Sass/SCSS support if needed
    //.enableSassLoader()

    // Enable TypeScript or React support if needed
    //.enableTypeScriptLoader()
    //.enableReactPreset()

;

module.exports = Encore.getWebpackConfig();

