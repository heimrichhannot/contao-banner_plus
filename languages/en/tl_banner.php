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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_banner']['addVisibility'][0] = 'Show banner on certain pages';
$GLOBALS['TL_LANG']['tl_banner']['addVisibility'][1] = 'Limiting the visibility of the banner for specific pages.';

$GLOBALS['TL_LANG']['tl_banner']['pages'][0] = 'Page filter';
$GLOBALS['TL_LANG']['tl_banner']['pages'][1] = 'Specify on which pages the banner should be displayed or not.';

$GLOBALS['TL_LANG']['tl_banner']['addPageDepth'][0] = 'Activate page inheritance';
$GLOBALS['TL_LANG']['tl_banner']['addPageDepth'][1] = 'Should the page filter be applied to child pages?';

$GLOBALS['TL_LANG']['tl_banner']['banner_image_left'][0] = 'Banner Image (left part / fireplace)';
$GLOBALS['TL_LANG']['tl_banner']['banner_image_left'][1] = 'Please select the banner for the left part.(GIF,JPG,PNG,SWF)';

$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_left']['0']      = 'Banner width and height (left part / fireplace)';
$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_left']['1']      = 'Here you can set the banner image dimensions (in pixel) and the resize mode (only for internal banner image). NOTE: Animated GIFs, with data sizes, the GD-recalculation outcome of this is a still picture.';

$GLOBALS['TL_LANG']['tl_banner']['banner_image_right'][0] = 'Banner Image (right part / fireplace)';
$GLOBALS['TL_LANG']['tl_banner']['banner_image_right'][1] = 'Please select the banner for the right part.(GIF,JPG,PNG,SWF)';

$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_right']['0']      = 'Banner width and height (right part / fireplace)';
$GLOBALS['TL_LANG']['tl_banner']['banner_imgSize_right']['1']      = 'Here you can set the banner image dimensions (in pixel) and the resize mode (only for internal banner image). NOTE: Animated GIFs, with data sizes, the GD-recalculation outcome of this is a still picture.';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_banner']['exclude'] = 'All pages except';
$GLOBALS['TL_LANG']['tl_banner']['include'] = 'Only on the following pages';

$GLOBALS['TL_LANG']['tl_banner_type']['banner_image_fireplace'] = 'Internal banner image (fireplace)';