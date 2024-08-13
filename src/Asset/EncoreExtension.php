<?php

namespace HeimrichHannot\BannerPlusBundle\Asset;

use HeimrichHannot\BannerPlusBundle\ContaoBannerPlusBundle;
use HeimrichHannot\EncoreContracts\EncoreEntry;
use HeimrichHannot\EncoreContracts\EncoreExtensionInterface;

class EncoreExtension implements EncoreExtensionInterface
{

    /**
     * @inheritDoc
     */
    public function getBundle(): string
    {
        return ContaoBannerPlusBundle::class;
    }

    /**
     * @inheritDoc
     */
    public function getEntries(): array
    {
        return [
            EncoreEntry::create('banner_plus-html-banner', 'src/Resources/assets/js/html-banner.js'),
        ];
    }
}