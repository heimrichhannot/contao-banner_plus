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

use HeimrichHannot\BannerPlusBundle\DataContainer\BannerContainer;

$dca = &$GLOBALS['TL_DCA']['tl_banner'];

/**
 * Palettes
 */
//$dca['palettes']['banner_image_fireplace'] = str_replace('banner_image', 'banner_image,banner_image_left,banner_image_right',$dca['palettes']['banner_image']);


$dca['list']['sorting']['child_record_callback'] = [BannerContainer::class, 'listBanner'];

/**
 * Palettes : add multiple fields
 */
foreach($dca['palettes'] as $strName => $strPalette)
{
	if($strName == '__selector__') continue;

	$dca['palettes'][$strName] = str_replace('banner_domain', 'addVisibility,pages,addPageDepth', $dca['palettes'][$strName]);
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
	'banner_image_left' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_banner']['banner_image_left'],
		'explanation'	          => 'banner_help',
		'inputType'               => 'fileTree',
		'sql'                     => "binary(16) NULL",
		'eval'                    => array('files'=>true, 'filesOnly'=>true, 'fieldType'=>'radio', 'extensions'=>'jpg,jpe,gif,png,swf', 'maxlength'=>255, 'helpwizard'=>true)
	),
	'banner_imgSize_left' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_left'],
		'exclude'                 => true,
		'inputType'               => 'imageSize',
		'options_callback'        => function() {
            return \Contao\System::getContainer()->get('contao.image.image_sizes')->getAllOptions();
        },
		'reference'               => &$GLOBALS['TL_LANG']['MSC'],
		'sql'                     => "varchar(255) NOT NULL default ''",
		'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
	),
	'banner_image_right' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_banner']['banner_image_right'],
		'explanation'	          => 'banner_help',
		'inputType'               => 'fileTree',
		'sql'                     => "binary(16) NULL",
		'eval'                    => array('files'=>true, 'filesOnly'=>true, 'fieldType'=>'radio', 'extensions'=>'jpg,jpe,gif,png,swf', 'maxlength'=>255, 'helpwizard'=>true)
	),
	'banner_imgSize_right' => array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_right'],
		'exclude'                 => true,
		'inputType'               => 'imageSize',
        'options_callback'        => function() {
            return \Contao\System::getContainer()->get('contao.image.image_sizes')->getAllOptions();
        },
		'reference'               => &$GLOBALS['TL_LANG']['MSC'],
		'sql'                     => "varchar(255) NOT NULL default ''",
		'eval'                    => array('rgxp'=>'digit', 'nospace'=>true)
	),
    'banner_animation' => array(
        'label'     => &$GLOBALS['TL_LANG']['tl_banner']['banner_animation'],
        'exclude'   => true,
        'inputType' => 'radio',
        'options'   => array('left', 'top'),
        'default'   => 'left',
        'reference' => &$GLOBALS['TL_LANG']['tl_banner'],
        'eval'      => array('submitOnChange' => true),
        'sql'       => "varchar(32) NOT NULL default ''",
    ),
);

$dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $arrFields);

$dca['palettes']['banner_image'] = str_replace('banner_imgSize', 'banner_imgSize,banner_image_left,banner_imgSize_left,banner_image_right,banner_imgSize_right,banner_animation', $dca['palettes']['banner_image']);
