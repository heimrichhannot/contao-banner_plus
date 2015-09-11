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

$dc = &$GLOBALS['TL_DCA']['tl_module'];

if (in_array('slick', \ModuleLoader::getActive()))
{
	$dc['palettes']['slick_newslist'] = str_replace(
		'skipFirst;',
		'skipFirst;{banner_legend},banner_hideempty,banner_firstview;banner_categories,banner_template;banner_redirect;guests,protected,banner_useragent;',
		$dc['palettes']['slick_newslist']
	);
}
