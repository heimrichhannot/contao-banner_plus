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


use Contao\System;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;

class InitializeSystemListener
{

    /**
     * @Hook("initializeSystem")
     */
    public function onInitializeSystem()
    {
        $this->addBackendAssets();

        if (class_exists('HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle')) {
            $GLOBALS['TL_HOOKS']['compileSlickNewsList']['huh_banner_plus'] = [CompileSlickNewsListListener::class, 'onCompileSlickNewsList'];
        }
        if(isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
        {
            foreach($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $key => $arrConfig)
            {
                if($arrConfig[0] == 'BugBuster\Banner\BannerInsertTag')
                {
                    $GLOBALS['TL_HOOKS']['replaceInsertTags'][$key] = [ReplaceInsertTagsListener::class, 'onReplaceInsertTags'];
                }
            }
        }
    }

    private function addBackendAssets()
    {
        $containerUtil = System::getContainer()->get(ContainerUtil::class);

        if ($containerUtil->isBackend()) {
            $GLOBALS['TL_JAVASCRIPT']['be_bannerplusbundle'] = 'bundles/contaobannerplus/assets/contao-banner-plus-bundle-be.js|static';
        }
    }
}