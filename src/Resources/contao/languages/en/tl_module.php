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

$lang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */

$lang['banner_plus_displayFormat'] = ['Banner display format','Specify how the banners should be displayed within the slider.'];
$lang['banner_plus_displayFormat'][\HeimrichHannot\BannerPlusBundle\DataContainer\ModuleContainer::DISPLAY_DEFAULT] = 'Default';
$lang['banner_plus_displayFormat'][\HeimrichHannot\BannerPlusBundle\DataContainer\ModuleContainer::DISPLAY_ROTATORY] = 'Rotatory';

/**
 * Legends
 */
$lang['banner_legend'] = 'Banner';