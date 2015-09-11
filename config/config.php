<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package banner_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


/**
 * -------------------------------------------------------------------------
 * HOOKS
 * -------------------------------------------------------------------------
 */

if(is_array($GLOBALS['TL_HOOKS']['replaceInsertTags']))
{
	foreach($GLOBALS['TL_HOOKS']['replaceInsertTags'] as $key => $arrConfig)
	{
		if($arrConfig[0] == 'Banner\ModuleBannerTag')
		{
			$GLOBALS['TL_HOOKS']['replaceInsertTags'][$key][0] = 'HeimrichHannot\Banner\ModuleBannerTag';
		}
	}
}

/**
 * Add support to slick slider for parorama ads
 */
if(in_array('slick', \ModuleLoader::getActive()))
{
	$GLOBALS['TL_HOOKS']['compileSlickNewsList'][] = array('HeimrichHannot\Banner\Hooks', 'compileSlickNewsListHook');
}