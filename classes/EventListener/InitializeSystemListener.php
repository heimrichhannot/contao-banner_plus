<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Banner\EventListener;


class InitializeSystemListener
{
    /**
     * @Hook("initializeSystem")
     */
    public function onInitializeSystem()
    {
        if (isset($GLOBALS['TL_HOOKS']['compileSlickNewsList'])) {
            $GLOBALS['TL_HOOKS']['compileSlickNewsList']['huh_banner_plus'] = [\HeimrichHannot\Banner\EventListener\CompileSlickNewsListListener::class, 'onCompileSlickNewsList'];
        }
        if(isset($GLOBALS['TL_HOOKS']['replaceInsertTags']) && is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
        {
            foreach($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $key => $arrConfig)
            {
                if($arrConfig[0] == 'BugBuster\Banner\BannerInsertTag')
                {
                    $GLOBALS['TL_HOOKS']['replaceInsertTags'][$key][0] = \HeimrichHannot\Banner\ModuleBannerTag::class;
                }
            }
        }
    }
}