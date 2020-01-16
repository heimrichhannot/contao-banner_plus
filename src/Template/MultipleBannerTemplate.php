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


use BugBuster\Banner\BannerMultiple;
use HeimrichHannot\BannerPlusBundle\Model\BannerModel;

class MultipleBannerTemplate extends BannerMultiple
{
    public function getMultiBanner($module_id)
    {
        $this->Template = parent::getMultiBanner($module_id);

        if(!is_array($this->Template->banners)) return $this->Template;

        $arrBanners = $this->Template->banners;

        foreach($arrBanners as $i => $arrBanner)
        {
            $objBanner = BannerModel::findByPk($arrBanner['banner_id']);

            if($objBanner === null) {
                continue;
            }

            if($objBanner->banner_type != static::BANNER_TYPE_INTERN) {
                continue;
            }

            $arrBanner['banner_animation'] = $objBanner->banner_animation;
            if ($objBanner->banner_animation) {
                $arrBanner['banner_animation'] = $objBanner->banner_animation;
            } else {
                $arrBanner['banner_animation'] = '';
            }

            $arrBanners[$i] = $arrBanner;
        }

        $this->Template->banners = $arrBanners;
        return $this->Template;
    }

}