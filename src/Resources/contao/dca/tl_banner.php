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
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;

$dca = &$GLOBALS['TL_DCA']['tl_banner'];

/**
 * Palettes
 */
//$dca['palettes']['banner_image_fireplace'] = str_replace('banner_image', 'banner_image,banner_image_left,banner_image_right',$dca['palettes']['banner_image']);

$dca['palettes'][HtmlType::BANNER_TYPE_HTML_INTERN] = 'banner_type;{title_legend},banner_name,banner_weighting;{destination_legend},banner_url,banner_jumpTo,banner_target;{image_legend},banner_html;{comment_legend},banner_comment;{filter_legend:hide},addVisibility,pages,addPageDepth;{expert_legend:hide},banner_cssid;{publish_legend},banner_published,banner_start,banner_stop,banner_until';
$dca['palettes'][HtmlType::BANNER_TYPE_HTML_EXTERN] = 'banner_type;{title_legend},banner_name,banner_weighting;{destination_legend},banner_url,banner_jumpTo,banner_target;{comment_legend},banner_comment;{filter_legend:hide},addVisibility,pages,addPageDepth;{expert_legend:hide},banner_cssid;{publish_legend},banner_published,banner_start,banner_stop,banner_until';

$dca['list']['sorting']['child_record_callback'] = [BannerContainer::class, 'listBanner'];

/**
 * Palettes : add multiple fields
 */
foreach ($dca['palettes'] as $strName => $strPalette) {
    if ($strName == '__selector__') continue;

    $dca['palettes'][$strName] = str_replace('banner_domain', 'addVisibility,pages,addPageDepth', $dca['palettes'][$strName]);
}



/**
 * Fields
 */
$arrFields =
    [
        'addVisibility' => [
            'exclude' => true,
            'inputType' => 'radio',
            'options' => ['exclude', 'include'],
            'default' => 'exclude',
            'reference' => &$GLOBALS['TL_LANG']['tl_banner'],
            'eval' => ['submitOnChange' => true],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'pages' => [
            'exclude' => true,
            'inputType' => 'pageTree',
            'eval' => ['fieldType' => 'checkbox', 'multiple' => true],
            'sql' => "blob NULL",
        ],
        'addPageDepth' => [
            'exclude' => true,
            'inputType' => 'checkbox',
            'default' => true,
            'eval' => ['tl_class' => 'm12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'banner_image_left' =>
            [
                'explanation' => 'banner_help',
                'inputType' => 'fileTree',
                'sql' => "binary(16) NULL",
                'eval' => ['files' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpe,gif,png,swf', 'maxlength' => 255, 'helpwizard' => true]
            ],
        'banner_imgSize_left' =>
            [
                'exclude' => true,
                'inputType' => 'imageSize',
                'options_callback' => function () {
                    return \Contao\System::getContainer()->get('contao.image.image_sizes')->getAllOptions();
                },
                'reference' => &$GLOBALS['TL_LANG']['MSC'],
                'sql' => "varchar(255) NOT NULL default ''",
                'eval' => ['rgxp' => 'digit', 'nospace' => true]
            ],
        'banner_image_right' =>
            [
                'explanation' => 'banner_help',
                'inputType' => 'fileTree',
                'sql' => "binary(16) NULL",
                'eval' => ['files' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'extensions' => 'jpg,jpe,gif,png,swf', 'maxlength' => 255, 'helpwizard' => true]
            ],
        'banner_imgSize_right' =>
            [
                'exclude' => true,
                'inputType' => 'imageSize',
                'options_callback' => function () {
                    return \Contao\System::getContainer()->get('contao.image.image_sizes')->getAllOptions();
                },
                'reference' => &$GLOBALS['TL_LANG']['MSC'],
                'sql' => "varchar(255) NOT NULL default ''",
                'eval' => ['rgxp' => 'digit', 'nospace' => true]
            ],
        'banner_animation' => [
            'exclude' => true,
            'inputType' => 'radio',
            'options' => ['left', 'top'],
            'default' => 'left',
            'reference' => &$GLOBALS['TL_LANG']['tl_banner'],
            'eval' => ['submitOnChange' => true],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'banner_html' => [
            'explanation' => 'banner_html',
            'inputType' => 'fileTree',
            'eval' => [
                'mandatory' => true,
                'files' => true,
                'filesOnly' => true,
                'fieldType' => 'radio',
                'extensions' => 'html,html5',
                'maxlength' => 255,
                'helpwizard' => true
            ],
            'sql' => "binary(16) NULL",
        ]
    ];

$dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $arrFields);

$dca['palettes']['banner_image'] = str_replace('banner_imgSize', 'banner_imgSize,banner_image_left,banner_imgSize_left,banner_image_right,banner_imgSize_right,banner_animation', $dca['palettes']['banner_image']);
