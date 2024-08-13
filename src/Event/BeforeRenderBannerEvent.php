<?php

namespace HeimrichHannot\BannerPlusBundle\Event;

use Contao\Template;
use Symfony\Contracts\EventDispatcher\Event;

class BeforeRenderBannerEvent extends Event
{
    public function __construct(
        public Template $template,
        public readonly array $context,
    )
    {
    }
}