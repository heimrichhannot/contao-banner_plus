<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\EventListener;


use Contao\FrontendTemplate;
use Contao\ModuleModel;
use HeimrichHannot\BannerPlusBundle\Template\SlickBannerTemplate;
use HeimrichHannot\SlickBundle\Module\ModuleSlickNewsList;

class CompileSlickNewsListListener
{

    /**
     * @param FrontendTemplate $objTemplate
     * @param ModuleSlickNewsList $frontendModule
     * @param ModuleModel $objModel
     */
    public function onCompileSlickNewsList(&$objTemplate, $frontendModule, $objModel)
    {
        $slickBannerGenerator = new SlickBannerTemplate();
        $slickBannerGenerator->generateSlickBanner($objTemplate, $frontendModule, $objModel);
    }
}