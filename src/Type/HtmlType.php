<?php
/**
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\BannerPlusBundle\Type;

use Contao\Database;
use Contao\FilesModel;
use Contao\StringUtil;

class HtmlType
{

    const BANNER_TEMPLATE = 'mod_banner_list_html_ad';
    const BANNER_TYPE_HTML_INTERN = 'banner_html_intern';
    const BANNER_TYPE_HTML_EXTERN = 'banner_html_extern';
    const BANNER_TYPES = [
        self::BANNER_TYPE_HTML_INTERN,
        self::BANNER_TYPE_HTML_EXTERN
    ];

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
            $bannerUrl = FilesModel::findByUuid(StringUtil::binToUuid($banner['banner_html']))->path;
        }

        return [
            "banner_key" => "bid=",
            "banner_wrap_id" => "",
            "banner_wrap_class" => "",
            "banner_id" => $banner['id'],
            "banner_name" => $banner['banner_name'],
            "banner_url" => $banner['banner_url'],
            "banner_target" => $banner['banner_target'] === '' ? ' target="_blank"': ' target="_self',
            "banner_comment" => $banner['comment'] ?: '',
            "src" => $bannerUrl,
            "alt" => '',
            "size" => '',
            "banner_pic" => false,
            "banner_flash" => false,
            "banner_text" => false,
            "banner_empty" => false,
            "banner_html" => true,
            "picture" => []
        ];
    }
}