<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\ContaoManager;


use BugBuster\BannerBundle\BugBusterBannerBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use HeimrichHannot\BannerPlusBundle\ContaoBannerPlusBundle;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;

class Plugin implements BundlePluginInterface
{

    /**
     * @inheritDoc
     */
    public function getBundles(ParserInterface $parser)
    {
        $loadAfter = [
            ContaoCoreBundle::class,
            BugBusterBannerBundle::class,
        ];
        if (class_exists('HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle')) {
            $loadAfter[] = HeimrichHannotContaoSlickBundle::class;
        }
        return [
            BundleConfig::create(ContaoBannerPlusBundle::class)->setLoadAfter($loadAfter),
        ];
    }
}