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


use HeimrichHannot\BannerPlusBundle\Template\BannerTemplate;

class ReplaceInsertTagsListener
{
    public function onReplaceInsertTags(string $insertTag)
    {
        $bannerGenerator = new BannerTemplate();
        return $bannerGenerator->replaceInsertTagsBanner($insertTag);
    }
}