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
 * -------------------------------------------------------------------------
 * HOOKS
 * -------------------------------------------------------------------------
 */




$GLOBALS['TL_HOOKS']['initializeSystem']['huh_banner_plus'] = [\HeimrichHannot\Banner\EventListener\InitializeSystemListener::class, 'onInitializeSystem'];