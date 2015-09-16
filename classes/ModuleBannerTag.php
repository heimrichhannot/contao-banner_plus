<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package anwaltverein
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Banner;


class ModuleBannerTag extends \BugBuster\Banner\ModuleBannerTag
{

	public function compileSlickNewsListHook(&$objTemplate, $objModule, $objModel)
	{
		$this->getModuleData($objModule->id);

		if($this->bannerHelperInit() === false)
		{
			$this->log('Problem in Bannerhelper::bannerHelperInit', 'HeimrichHannot\Banner\Hooks compileSlickNewsListHook', TL_ERROR);
			return false;
		}

		$this->typePrefix = 'mod_';
		$this->class = 'mod_banner';

		$this->article_class = $this->class[1];
		$this->article_cssID = $this->cssID[0];
		$this->article_style = $this->style;

		if ($this->statusBannerFrontendGroupView === false)
		{
			// Eingeloggter FE Nutzer darf nichts sehen, falsche Gruppe
			return false;
		}

		$this->Template = new \FrontendTemplate($this->strTemplate);

		if ($this->statusAllBannersBasic === false)
		{
			//keine Banner vorhanden in der Kategorie
			//default Banner holen
			//kein default Banner, ausblenden wenn leer?
			$this->getDefaultBanner();
		}

		//OK, Banner vorhanden, dann weiter
		//BannerSeen vorhanden? Dann beachten.
		if ( count(self::$arrBannerSeen) )
		{
			//$arrAllBannersBasic dezimieren um die bereits angezeigten
			foreach (self::$arrBannerSeen as $BannerSeenID)
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
				$this->setCssClassIdStyle();
			}
		}

		//OK, noch Banner übrig, weiter gehts
		//Single Banner?
		if ($this->arrCategoryValues['banner_numbers'] != 1)
		{
			//FirstViewBanner?
			if ($this->getSetFirstView() === true)
			{
				$this->getSingleBannerFirst();
			}
			else
			{
				//single banner
				$this->getSingleBanner();
			}
		}
		else
		{
			//multi banner
			$this->getMultiBanner();
		}

		$arrArticles = $objTemplate->articles;
		$arrBanners = $this->Template->banners;
		$arrBannerWeight = $this->arrAllBannersBasic;

		if(is_array($arrBanners))
		{
			foreach($arrBanners as $arrData)
			{
				$this->setCssClassIdStyle();
				$this->Template->banners = array($arrData);
				$weight = $arrBannerWeight[$arrData['banner_id']];
				
				switch($weight){
					// highest priority -> prepend
					case 1:
						array_unshift($arrArticles, $this->Template->parse());
					break;
					// normal priority -> middle
					case 2:
						array_insert($arrArticles, ceil(count($arrArticles) / 2), array($this->Template->parse()));
					break;
					// lowest priority -> append
					case 3:
						array_insert($arrArticles, count($arrArticles), array($this->Template->parse()));
					break;
				}
			}
		}
		
		$objTemplate->articles = $arrArticles;
	}

	/**
	 * Wrapper for backward compatibility
	 *
	 * @param integer $moduleId
	 * @return boolean
	 */
	protected function getModuleData($moduleId)
	{
		$this->module_id = $moduleId; //for RandomBlocker Session
		//DEBUG log_message('getModuleData Banner Modul ID:'.$moduleId,'Banner.log');
		$objBannerModule = \Database::getInstance()->prepare("SELECT
                                                                    banner_hideempty,
                                                        	        banner_firstview,
                                                        	        banner_categories,
                                                        	        banner_template,
                                                        	        banner_redirect,
                                                        	        banner_useragent,
                                                                    cssID,
                                                                    space,
                                                                    headline
                                                                FROM
                                                                    tl_module
                                                                WHERE
                                                                    id=? AND banner_categories !=''")
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
		$this->space             = $objBannerModule->space;
		$this->headline          = $objBannerModule->headline;
		return true;
	}

	/**
	 * Overwrite BugBuster\Banner\BannerHelper::getSetAllBannerForCategory()
	 * to provide custom logic
	 * - add page filter
	 *
	 * @return bool
	 */
	protected function getSetAllBannerForCategory()
	{
		$this->arrAllBannersBasic = array();
		//wenn mit der definierte Kategorie ID keine Daten gefunden wurden
		//macht Suche nach Banner kein Sinn
		if ($this->arrCategoryValues === false)
		{
			return false;
		}
		//Domain Name ermitteln
		$http_host = \Environment::get('host');
		//aktueller Zeitstempel
		$intTime = time();

		//alle gültigen aktiven Banner,
		//ohne Beachtung der Gewichtung,
		//mit Beachtung der Domain
		//sortiert nach "sorting"
		//nur Basic Felder `id`, `banner_weighting`
		$objBanners = \Database::getInstance()
			->prepare("SELECT
                                        TLB.*
                                   FROM
                                        tl_banner AS TLB
                                   LEFT JOIN
                                        tl_banner_category ON tl_banner_category.id=TLB.pid
                                   LEFT OUTER JOIN
                                        tl_banner_stat AS TLS ON TLB.id=TLS.id
                                   WHERE
                                        pid=?
                                   AND (
                                           (TLB.banner_until=?)
		                                OR (TLB.banner_until=1 AND TLB.banner_views_until>TLS.banner_views)
                                        OR (TLB.banner_until=1 AND TLB.banner_views_until=?)
                                        OR (TLB.banner_until=1 AND TLS.banner_views is NULL)
                                       )
                                   AND (
                                           (TLB.banner_until=?)
                                        OR (TLB.banner_until=1 AND TLB.banner_clicks_until>TLS.banner_clicks)
                                        OR (TLB.banner_until=1 AND TLB.banner_clicks_until=?)
                                        OR (TLB.banner_until=1 AND TLS.banner_clicks is NULL)
                                       )
                                   AND
                                        TLB.banner_published =?
                                   AND
                                       (TLB.banner_start=? OR TLB.banner_start<=?)
                                   AND
                                       (TLB.banner_stop=? OR TLB.banner_stop>=?)
                                   ORDER BY TLB.`sorting`"
			)
			->execute($this->banner_categories
				, '', ''
				, '', ''
				, 1
				, '', $intTime, '', $intTime
				, '', $http_host);

		while ($objBanners->next())
		{
			if(!$this->isVisible($objBanners)) continue;

			$this->arrAllBannersBasic[$objBanners->id] = $objBanners->banner_weighting;
		}
		//DEBUG log_message('getSetAllBannerForCategory arrAllBannersBasic:'.print_r($this->arrAllBannersBasic,true),'Banner.log');
		return (bool)$this->arrAllBannersBasic; //false bei leerem array, sonst true
	}


	protected function isVisible($objBanners)
	{
		global $objPage;

		$arrPages = deserialize($objBanners->pages);
		
		/**
		 * Filter out pages
		 * (exclude == display module not on this page)
		 * (include == display module only on this page)
		 */
		if(is_array($arrPages) && count($arrPages) > 0)
		{
			// add nested pages to the filter
			if($objBanners->addPageDepth)
			{
				$arrPages = array_merge($arrPages, \Database::getInstance()->getChildRecords($arrPages, 'tl_page'));
			}


			$check = ($objBanners->addVisibility == 'exclude') ? true : false;

			if(in_array($objPage->id, $arrPages) == $check)
			{
				return false;
			}
		}

		return true;
	}

}