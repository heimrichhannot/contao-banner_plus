<?php

$lang = &$GLOBALS['TL_LANG']['XPL'];

$lang['banner_plus_displayFormat'] = [
    [$GLOBALS['TL_LANG']['tl_module']['banner_plus_displayFormat'][\HeimrichHannot\Banner\DataContainer\ModuleContainer::DISPLAY_DEFAULT], 'Banner mit hoher Priorität werden am Anfang angezeigt, Banner mit mittlerer Priorität in der Mitte, Banner mit geringer Prioriät am Ende. Beliebig viele Banner möglich.'],
    [$GLOBALS['TL_LANG']['tl_module']['banner_plus_displayFormat'][\HeimrichHannot\Banner\DataContainer\ModuleContainer::DISPLAY_ROTATORY], 'Inhalte und Banner werden Abwechselnd angezeigt, wobei das letzte Element ein Inhaltselement ist. Es wird ein Banner weniger angezeigt, als Inhaltselemente vorhanden. Es werden die Banner mit der höchsten Priorität angezeigt.',]
];