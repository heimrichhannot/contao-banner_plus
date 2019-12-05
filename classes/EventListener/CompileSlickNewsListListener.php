<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Banner\EventListener;


use Contao\FrontendTemplate;
use Contao\ModuleModel;
use HeimrichHannot\Banner\ModuleBannerTag;
use HeimrichHannot\SlickBundle\ModuleSlickNewsList;

class CompileSlickNewsListListener
{

    /**
     * @param FrontendTemplate $objTemplate
     * @param ModuleSlickNewsList $objModule
     * @param ModuleModel $objModel
     */
    public function onCompileSlickNewsList(&$objTemplate, $objModule, $objModel)
    {
        $template = new ModuleBannerTag();
        return $template->compileSlickNewsListHook($objTemplate, $objModule, $objModel);
    }
}