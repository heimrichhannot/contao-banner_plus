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
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use HeimrichHannot\BannerPlusBundle\HeimrichHannotBannerPlusBundle;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface, RoutingPluginInterface
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
            BundleConfig::create(HeimrichHannotBannerPlusBundle::class)->setLoadAfter($loadAfter),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@ContaoBannerPlusBundle/Resources/config/services.yml');
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver->resolve(__DIR__.'/../Resources/config/routes.yaml')->load(__DIR__.'/../Resources/config/routes.yaml');
    }
}