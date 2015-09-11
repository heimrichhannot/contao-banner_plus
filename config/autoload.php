<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'HeimrichHannot\Banner\ModuleBannerTag' => 'system/modules/banner_plus/classes/ModuleBannerTag.php',
	'HeimrichHannot\Banner\Hooks'           => 'system/modules/banner_plus/classes/Hooks.php',
));
