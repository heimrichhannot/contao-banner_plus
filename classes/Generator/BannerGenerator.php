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


use BugBuster\Banner\BannerHelper;
use BugBuster\Banner\BannerInsertTag;
use BugBuster\Banner\BannerLog;
use BugBuster\Banner\BannerLogic;
use Contao\Database;
use HeimrichHannot\Banner\Template\MultipleBannerTemplate;
use HeimrichHannot\Banner\Template\SingleBannerTemplate;

class BannerGenerator extends BannerInsertTag
{
    /**
     * getModuleData
     *
     * Wrapper for backward compatibility
     *
     * @param integer $moduleId
     * @return boolean
     */
    protected function getModuleData($moduleId)
    {
        $this->module_id = $moduleId; //for RandomBlocker Session
        $objBannerModule = Database::getInstance()->prepare("SELECT 
                                                                    banner_hideempty,
                                                        	        banner_firstview,
                                                        	        banner_categories,
                                                        	        banner_template,
                                                        	        banner_redirect,
                                                        	        banner_useragent,
                                                                    cssID,
                                                                    headline 
                                                                FROM  
                                                                    tl_module 
                                                                WHERE 
                                                                    id=?")
            ->execute($moduleId);
        if ($objBannerModule->numRows == 0)
        {
            return false;
        }
        $this->banner_hideempty  = $objBannerModule->banner_hideempty;
        $this->banner_firstview  = $objBannerModule->banner_firstview;
        $this->banner_categories = $objBannerModule->banner_categories;
        $this->banner_template   = $objBannerModule->banner_template;
        $this->banner_redirect   = $objBannerModule->banner_redirect;
        $this->banner_useragent  = $objBannerModule->banner_useragent;
        $this->cssID             = $objBannerModule->cssID;
        $this->headline          = $objBannerModule->headline;
        return true;
    }

    /**
     * generateBanner
     *
     * @return boolean|string
     */
    protected function generateBanner()
    {
        //DEBUG log_message('generateBanner banner_categories:'.$this->banner_categories,'Banner.log');
        if ($this->bannerHelperInit() === false)
        {
            BannerLog::log('Problem in bannerHelperInit', 'ModuleBannerTag generateBanner', TL_ERROR);
            return false;
        }

        if ($this->statusBannerFrontendGroupView === false)
        {
            // Eingeloggter FE Nutzer darf nichts sehen, falsche Gruppe
            // auf Leer umschalten
            $this->strTemplate='mod_banner_empty';
            $this->Template = new \FrontendTemplate($this->strTemplate);
            return $this->Template->parse();
        }
        $this->Template = new \FrontendTemplate($this->strTemplate);

        if ($this->statusAllBannersBasic === false)
        {
            //keine Banner vorhanden in der Kategorie
            //default Banner holen
            //kein default Banner, ausblenden wenn leer?
            $this->getDefaultBanner();
            //Css generieren
            $this->setCssClassIdStyle();
            //Template parsen und Ergebnis zurückgeben
            return $this->Template->parse();
        }

        //OK, Banner vorhanden, dann weiter
        //BannerSeen vorhanden? Dann beachten.
        if ( count(BannerHelper::$arrBannerSeen) )  //TODO
        {
            //$arrAllBannersBasic dezimieren um die bereits angezeigten
            foreach (BannerHelper::$arrBannerSeen as $BannerSeenID)
            {
                if (array_key_exists($BannerSeenID,$this->arrAllBannersBasic))
                {
                    unset($this->arrAllBannersBasic[$BannerSeenID]);
                };
            }
            //noch Banner übrig?
            if ( count($this->arrAllBannersBasic) == 0 )
            {
                //default Banner holen
                //kein default Banner, ausblenden wenn leer?
                $this->getDefaultBanner();
                //Css generieren
                $this->setCssClassIdStyle();
                return $this->Template->parse();
            }
        }

        //OK, noch Banner übrig, weiter gehts
        //Single Banner?
        if ($this->arrCategoryValues['banner_numbers'] != 1)
        {
            //FirstViewBanner?
            $objBannerLogic = new BannerLogic();

            if ($objBannerLogic->getSetFirstView($this->banner_firstview,$this->banner_categories,$this->module_id) === true)
            {
                $this->getSingleBannerFirst();
                //Css generieren
                $this->setCssClassIdStyle();
                return $this->Template->parse();
            }
            else
            {
                //single banner
                $this->getSingleBanner();
                //Css generieren
                $this->setCssClassIdStyle();
                return $this->Template->parse();
            }
        }
        else
        {
            //multi banner
            $this->getMultiBanner();
            //Css generieren
            $this->setCssClassIdStyle();
            return $this->Template->parse();
        }

    }

    protected function getDefaultBanner(): void
    {
        $objBannerSingle = new SingleBannerTemplate($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
        $this->Template  = $objBannerSingle->getDefaultBanner($this->banner_hideempty);
    }

    protected function getSingleBannerFirst(): void
    {
        $objBannerSingle = new SingleBannerTemplate($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
        $this->Template  = $objBannerSingle->getSingleBannerFirst($this->module_id);
    }

    protected function getSingleBanner(): void
    {
        $objBannerSingle = new SingleBannerTemplate($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
        $this->Template  = $objBannerSingle->getSingleBanner($this->module_id);
    }

    protected function getMultiBanner(): void
    {
        $objBannerMultiple = new MultipleBannerTemplate($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
        $this->Template    = $objBannerMultiple->getMultiBanner($this->module_id);
    }
}