<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2019 Leo Feyer
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
	'HeimrichHannot\Banner\SliderDisplayFormat'           => 'system/modules/banner_plus/classes/SliderDisplayFormat.php',
	'HeimrichHannot\Banner\DataContainer\ModuleContainer' => 'system/modules/banner_plus/classes/DataContainer/ModuleContainer.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_banner_list_fireplace_ad' => 'system/modules/banner_plus/templates/banner',
));
