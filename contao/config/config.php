<?php
/**
 * Contao Open Source CMS
 * 
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package banner_plus
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


use HeimrichHannot\BannerPlusBundle\Model\BannerCategoryModel;
use HeimrichHannot\BannerPlusBundle\Model\BannerModel;

$GLOBALS['TL_MODELS']['tl_banner'] = BannerModel::class;
$GLOBALS['TL_MODELS']['tl_banner_category'] = BannerCategoryModel::class;

