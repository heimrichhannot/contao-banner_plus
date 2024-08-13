<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\EventListener\Contao;


use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;

#[AsHook("loadDataContainer")]
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {
        switch ($table) {
            case 'tl_banner':
                $this->updateBannerDataContainer($table);
                break;
        }
    }

    public function updateBannerDataContainer(string $table): void
    {
        $dca = &$GLOBALS['TL_DCA'][$table];
        $options = HtmlType::BANNER_TYPES;
        $dca['fields']['banner_type']['options'] = array_merge(is_array($dca['fields']['banner_type']['options']) ? $dca['fields']['banner_type']['options'] : [], $options);
    }
}