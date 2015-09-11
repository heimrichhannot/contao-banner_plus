<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package banner_plus
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Banner;


class Hooks extends \Controller
{

	public static function compileSlickNewsListHook(&$objTemplate, $objModule, $objModel)
	{
		$objModel->type = 'banner';

		$objBanner = new \BugBuster\Banner\ModuleBanner($objModel);
		$objBanner->typePrefix = 'mod_';
		$objBanner->class = 'mod_banner';
		$arrArticles = $objTemplate->articles;

		array_insert($arrArticles, 0 , $objBanner->generate());

		$objTemplate->articles = $arrArticles;
	}
}