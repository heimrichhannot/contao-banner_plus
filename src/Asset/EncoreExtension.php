<?php

namespace HeimrichHannot\BannerPlusBundle\Asset;

use HeimrichHannot\BannerPlusBundle\HeimrichHannotBannerPlusBundle;
use HeimrichHannot\EncoreContracts\EncoreEntry;
use HeimrichHannot\EncoreContracts\EncoreExtensionInterface;

class EncoreExtension implements EncoreExtensionInterface
{

    /**
     * @inheritDoc
     */
    public function getBundle(): string
    {
        return HeimrichHannotBannerPlusBundle::class;
    }

    /**
     * @inheritDoc
     */
    public function getEntries(): array
    {
        return [
            EncoreEntry::create('banner_plus-html-banner', 'assets/js/html-banner.js'),
        ];
    }
}