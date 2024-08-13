var Encore = require('@symfony/webpack-encore');

Encore
.setOutputPath('src/Resources/public/assets')
.setPublicPath('/bundles/heimrichhannotbannerplusbundle/assets/')
.setManifestKeyPrefix('bundles/heimrichhannotbannerplusbundle/assets')
.addEntry('contao-banner-plus-bundle-be', './src/Resources/assets/js/contao-banner-plus-bundle-be.js')
.addEntry('banner_plus-html-banner', './src/Resources/assets/js/html-banner.js')
.disableSingleRuntimeChunk()
.splitEntryChunks()
.configureSplitChunks(function(splitChunks) {
    splitChunks.name =  function (module, chunks, cacheGroupKey) {
        const moduleFileName = module.identifier().split('/').reduceRight(item => item).split('.').slice(0, -1).join('.');
        return `${moduleFileName}`;
    };
})
.configureBabel(null)
.enableSourceMaps(!Encore.isProduction())
.enableSassLoader()
.enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();