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

$table = 'tl_module';
$dc = &$GLOBALS['TL_DCA'][$table];

$dc['fields']['banner_plus_displayFormat'] = [
    'label'     => &$GLOBALS['TL_LANG'][$table]['banner_plus_displayFormat'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => [
        \HeimrichHannot\BannerPlusBundle\DataContainer\ModuleContainer::DISPLAY_DEFAULT,
        \HeimrichHannot\BannerPlusBundle\DataContainer\ModuleContainer::DISPLAY_ROTATORY,
    ],
    'reference' => &$GLOBALS['TL_LANG'][$table]['banner_plus_displayFormat'],
    'eval'      => ['includeBlankOption' => true, 'tl_class' => 'w50 clr', 'helpwizard'=>true,],
    'sql'       => "varchar(10) NOT NULL default ''",
    'explanation'   => 'banner_plus_displayFormat'
];

$dc['fields']['banner_redirect']['eval']['tl_class'] .= ' clr';
