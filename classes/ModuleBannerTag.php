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