<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\Template;


use BugBuster\Banner\BannerLog;
use Contao\FrontendTemplate;
use Contao\Module;
use Contao\ModuleModel;
use HeimrichHannot\BannerPlusBundle\Helper\SliderDisplayFormatHelper;
use HeimrichHannot\SlickBundle\Module\ModuleSlickEventList;
use HeimrichHannot\SlickBundle\Module\ModuleSlickNewsList;

/**
 * Class SlickBannerGenerator
 * @package HeimrichHannot\BannerPlusBundle\Helper\Generator
 *
 * @property FrontendTemplate $Template
 */
class SlickBannerTemplate extends BannerTemplate
{
    /**
     * @param FrontendTemplate $template
     * @param Module|ModuleSlickNewsList $frontendModule
     * @param ModuleModel $model The slick module model
     * @return bool
     */
    public function generateSlickBanner(&$template, $frontendModule, $model)
    {
        $this->getModuleData($frontendModule->id, ['banner', ModuleSlickNewsList::TYPE, ModuleSlickEventList::TYPE]);
        if (false === $this->bannerHelperInit())
        {
            //kein Banner Modul mit dieser ID
            BannerLog::log('No banner module with this id "'.$frontendModule->id.'"', __METHOD__ .':'. __LINE__, TL_ERROR);
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
            $style = new SliderDisplayFormatHelper($template->articles, $this->Template->banners, $this->arrAllBannersBasic, $this->Template);
            $template->articles = $style->format($frontendModule->banner_plus_displayFormat);
        }
    }
}