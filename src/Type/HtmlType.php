<?php
/**
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\BannerPlusBundle\Type;

use Contao\Database;
use Contao\StringUtil;
use HeimrichHannot\EncoreContracts\PageAssetsTrait;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class HtmlType implements ServiceSubscriberInterface
{
    use PageAssetsTrait;

    const BANNER_TYPE_HTML_INTERN = 'banner_html_intern';
    const BANNER_TYPE_HTML_EXTERN = 'banner_html_extern';
    const BANNER_TYPES = [
        self::BANNER_TYPE_HTML_INTERN,
        self::BANNER_TYPE_HTML_EXTERN
    ];

    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function prepare($banners, $bannerBasic): array
    {
        $banner_keys = array_keys($bannerBasic);
        $banner_id   = array_shift($banner_keys);

        $banner = Database::getInstance()->prepare("SELECT TLB.* FROM tl_banner AS TLB WHERE TLB.`id`=?")
            ->limit(1)
            ->execute( $banner_id );

        if (in_array($banner->row()['banner_type'], HtmlType::BANNER_TYPES)) {
            $banners = array_merge(is_array($banners) ? $banners : [] , [$this->generateTemplate($banner->row())]) ;
        }

        return $banners;
    }

    public function generateTemplate(array $banner): array
    {
        $bannerUrl = '';

        if ($banner['banner_type'] === static::BANNER_TYPE_HTML_EXTERN) {
            $bannerUrl = $banner['banner_url'];
        } elseif ($banner['banner_type'] === static::BANNER_TYPE_HTML_INTERN) {
            $bannerUrl = $this->router->generate('bannerplus_html_banner', ['id' => $banner['id']]);
            $this->addPageEntrypoint('banner_plus-html-banner', [
                'TL_JAVASCRIPT' => [
                    'banner_plus-html-banner' => 'bundles/heimrichhannotbannerplus/assets/banner_plus-html-banner.js',
                ],
            ]);
        }

        $cssId = StringUtil::deserialize($banner['banner_cssid'], true);

        return [
            "banner_key" => "bid",
            "banner_wrap_id" => $cssId[0] ? 'id="' . $cssId[0] . '"' : '',
            "banner_wrap_class" => $cssId[1] ? ' ' . $cssId[1] : '',
            "banner_id" => $banner['id'],
            "banner_name" => $banner['banner_name'],
            "banner_url" => $banner['banner_url'],
            "banner_target" => $banner['banner_target'] === '' ? ' target="_blank"' : ' target="_self',
            "banner_comment" => $banner['comment'] ?: '',
            "src" => $bannerUrl,
            "alt" => '',
            "size" => '',
            "banner_start" => $banner['banner_start'],
            "banner_end" => $banner['banner_end'],
            "banner_pic" => false,
            "banner_flash" => false,
            "banner_text" => false,
            "banner_empty" => false,
            "banner_html" => true,
            "picture" => []
        ];
    }
}
