<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Banner\Generator;


use BugBuster\Banner\BannerLog;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\ModuleModel;
use HeimrichHannot\Banner\SliderDisplayFormat;
use HeimrichHannot\SlickBundle\ModuleSlickNewsList;

/**
 * Class SlickBannerGenerator
 * @package HeimrichHannot\Banner\Generator
 *
 * @property FrontendTemplate $Template
 */
class SlickBannerGenerator extends BannerGenerator
{
    /**
     * @param FrontendTemplate $template
     * @param Module|ModuleSlickNewsList $frontendModule
     * @param ModuleModel $model The slick module model
     * @return bool
     */
    public function generateSlickBanner(&$template, $frontendModule, $model)
    {
        $retModuleData = $this->getModuleData($frontendModule->id);
        if (false === $retModuleData)
        {
            //kein Banner Modul mit dieser ID
            BannerLog::log('No banner module with this id "'.$frontendModule->id.'"', 'CompileSlickNewsListListener onCompileSlickNewsList', TL_ERROR);
            return false;
        }

        $this->typePrefix = 'mod_';
        $this->class = 'mod_banner';

        $this->article_class = $this->class[1];
        $this->article_cssID = $this->cssID[0];
        $this->article_style = $this->style;
        $this->banner_imgSize = $model->imgSize;

        $this->generateBanner();

        if(is_array($this->Template->banners))
        {
            $style = new SliderDisplayFormat($template->articles, $this->Template->banners, $this->arrAllBannersBasic, $this);
            $template->articles = $style->format($frontendModule->banner_plus_displayFormat);
        }
    }
}