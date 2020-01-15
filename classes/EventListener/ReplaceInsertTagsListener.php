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


use HeimrichHannot\Banner\Generator\BannerGenerator;

class ReplaceInsertTagsListener
{
    /**
     * @Hook("replaceInsertTags")
     */
    public function onReplaceInsertTags(string $insertTag)
    {
        $bannerGenerator = new BannerGenerator();
        return $bannerGenerator->replaceInsertTagsBanner($insertTag);
    }
}