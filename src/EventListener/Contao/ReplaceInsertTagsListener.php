<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\EventListener\Contao;


use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use HeimrichHannot\BannerPlusBundle\Template\BannerTemplate;

#[AsHook("replaceInsertTags", priority: 10)]
class ReplaceInsertTagsListener
{
    public function __invoke(string $insertTag)
    {
        $bannerGenerator = new BannerTemplate();
        return $bannerGenerator->replaceInsertTagsBanner($insertTag);
    }
}