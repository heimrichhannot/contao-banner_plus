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


use Contao\ModuleLoader;

class LoadDataContainerListener
{
    /**
     * @Hook("loadDataContainer")
     */
    public function onLoadDataContainer(string $table): void
    {
        switch ($table) {
            case 'tl_module':
                $this->updateModuleDataContainer();
                break;
        }
    }

    public function updateModuleDataContainer()
    {
        $dc = &$GLOBALS['TL_DCA']['tl_module'];
        $active = ModuleLoader::getActive();
        if (in_array('slick', $active) || in_array('HeimrichHannotContaoSlickBundle', $active))
        {
            $dc['palettes']['slick_newslist'] = str_replace(
                'skipFirst;',
                'skipFirst;{banner_legend},banner_hideempty,banner_firstview,banner_categories,banner_template,banner_plus_displayFormat,banner_redirect,guests,protected,banner_useragent;',
                $dc['palettes']['slick_newslist']
            );
        }
    }
}