<?php

$lang = &$GLOBALS['TL_LANG']['XPL'];

$lang['banner_plus_displayFormat'] = [
    [$GLOBALS['TL_LANG']['tl_module']['banner_plus_displayFormat'][\HeimrichHannot\Banner\DataContainer\ModuleContainer::DISPLAY_DEFAULT], 'Banners with high priority will be displayed at the beginning of the slider, banners with normal prority in the middle, banners with low priority at the end. No banner count limit.'],
    [$GLOBALS['TL_LANG']['tl_module']['banner_plus_displayFormat'][\HeimrichHannot\Banner\DataContainer\ModuleContainer::DISPLAY_ROTATORY], 'Content and banners will be display rotatary. First and last slide will be a content element. Only as much banners as content elements minus one will be displayed, choosen by priority.',]
];