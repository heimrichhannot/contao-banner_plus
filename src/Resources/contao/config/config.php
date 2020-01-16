<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package banner_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


$GLOBALS['TL_HOOKS']['initializeSystem']['huh_banner_plus'] = [\HeimrichHannot\BannerPlusBundle\EventListener\InitializeSystemListener::class, 'onInitializeSystem'];
$GLOBALS['TL_HOOKS']['loadDataContainer']['huh_banner_plus'] = [\HeimrichHannot\BannerPlusBundle\EventListener\LoadDataContainerListener::class, 'onLoadDataContainer'];

$GLOBALS['TL_MODELS']['tl_banner'] = \HeimrichHannot\BannerPlusBundle\Model\BannerModel::class;

