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

$dc = &$GLOBALS['TL_DCA']['tl_banner_category'];

/**
 * Palettes
 */
$dc['palettes']['default'] = str_replace('banner_numbers', 'banner_numbers, banner_mediaquery', $dc['palettes']['default']);


/**
 * Fields
 */
$arrFields = array
(
	'banner_mediaquery' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_banner_category']['banner_mediaquery'],
		'inputType'               => 'text',
		'exclude'                 => true,
		'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'decodeEntities'=>true),
		'sql'                     => "varchar(255) NOT NULL default ''"
	),
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);