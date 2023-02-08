<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\EventListener;


use Contao\CoreBundle\ServiceAnnotation\Hook;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;
use HeimrichHannot\UtilsBundle\Util\Utils;

/**
 * @Hook("initializeSystem")
 */
class InitializeSystemListener
{
    private Utils $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Hook("initializeSystem")
     */
    public function onInitializeSystem()
    {
        $this->addBackendAssets();

        if (class_exists(HeimrichHannotContaoSlickBundle::class)) {
            $GLOBALS['TL_HOOKS']['compileSlickNewsList']['huh_banner_plus'] = [CompileSlickNewsListListener::class, 'onCompileSlickNewsList'];
        }
        if (isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags'])) {
            foreach ($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $key => $arrConfig) {
                if ($arrConfig[0] == 'BugBuster\Banner\BannerInsertTag') {
                    $GLOBALS['TL_HOOKS']['replaceInsertTags'][$key] = [ReplaceInsertTagsListener::class, 'onReplaceInsertTags'];
                }
            }
        }
    }

    private function addBackendAssets()
    {
        if ($this->utils->container()->isBackend()) {
            $GLOBALS['TL_JAVASCRIPT']['be_bannerplusbundle'] = 'bundles/contaobannerplus/assets/contao-banner-plus-bundle-be.js|static';
        }
    }
}