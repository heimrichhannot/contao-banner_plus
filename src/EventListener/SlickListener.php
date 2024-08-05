<?php

namespace HeimrichHannot\BannerPlusBundle\EventListener;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SlickListener
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    )
    {
    }

    #[AsHook("loadDataContainer")]
    public function onLoadDataContainer(string $table): void
    {
        if ('tl_module' !== $table) {
            return;
        }

        $bundles = array_keys($this->parameterBag->get('kernel.bundles'));
        if (!in_array('slick', $bundles) && !in_array(HeimrichHannotContaoSlickBundle::class, $bundles)) {
            return;
        }

        PaletteManipulator::create()
            ->addLegend('banner_legend', 'skipFirst', PaletteManipulator::POSITION_AFTER)
            ->addField('banner_hideempty', 'banner_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_firstview', 'banner_hideempty', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_categories', 'banner_firstview', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_template', 'banner_categories', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_plus_displayFormat', 'banner_template', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_redirect', 'banner_plus_displayFormat', PaletteManipulator::POSITION_APPEND)
            ->addField('guests', 'banner_redirect', PaletteManipulator::POSITION_APPEND)
            ->addField('protected', 'guests', PaletteManipulator::POSITION_APPEND)
            ->addField('banner_useragent', 'protected', PaletteManipulator::POSITION_APPEND)
            ->applyToPalette('slick_newslist', 'tl_module');
    }
}