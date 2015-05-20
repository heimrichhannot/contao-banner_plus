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

$dc = &$GLOBALS['TL_DCA']['tl_banner'];

// add fields to multiple palettes
foreach($dc['palettes'] as $strName => $strPalette)
{
	if($strName == '__selector__') continue;

	$dc['palettes'][$strName] = str_replace('banner_domain', 'addVisibility,pages,addPageDepth', $dc['palettes'][$strName]);
}

/**
 * Fields
 */
$arrFields = array
(
	'addVisibility' => array(
		'label'     => &$GLOBALS['TL_LANG']['tl_banner']['addVisibility'],
		'exclude'   => true,
		'inputType' => 'radio',
		'options'   => array('exclude', 'include'),
		'default'   => 'exclude',
		'reference' => &$GLOBALS['TL_LANG']['tl_banner'],
		'eval'      => array('submitOnChange' => true),
		'sql'       => "varchar(32) NOT NULL default ''",
	),
	'pages'         => array(
		'label'     => &$GLOBALS['TL_LANG']['tl_banner']['pages'],
		'exclude'   => true,
		'inputType' => 'pageTree',
		'eval'      => array('fieldType' => 'checkbox', 'multiple' => true),
		'sql'       => "blob NULL",
	),
	'addPageDepth'        => array(
		'label'     => &$GLOBALS['TL_LANG']['tl_banner']['addPageDepth'],
		'exclude'   => true,
		'inputType' => 'checkbox',
		'default'   => true,
		'eval'      => array('tl_class' => 'm12'),
		'sql'       => "char(1) NOT NULL default ''",
	),
);

$dc['fields'] = array_merge($dc['fields'], $arrFields);