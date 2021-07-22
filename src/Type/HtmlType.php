<?php
/**
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\BannerPlusBundle\Type;

use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;

class HtmlType
{

    public function generateTemplate(Template $template, array $banner)
    {

        $htmlFile = FilesModel::findByUuid(StringUtil::binToUuid($banner['banner_html']));

        if (!is_array($template->banners)) {
            $template->banners = [];
        }
        $banners = [[
            "banner_key" => "bid=",
            "banner_wrap_id" => "",
            "banner_wrap_class" => "",
            "banner_id" => $banner['id'],
            "banner_name" => $banner['banner_name'],
            "banner_url" => $banner['banner_url'],
            "banner_target" => $banner['banner_target'] === '' ? ' target="_blank"': ' target="_self',
            "banner_comment" => $banner['comment'] ?: '',
            "src" => $htmlFile->path,
            "alt" => '',
            "size" => '',
            "banner_pic" => false,
            "banner_flash" => false,
            "banner_text" => false,
            "banner_empty" => false,
            "banner_html" => true,
            "picture" => []
        ]];

        $template->banners = $banners;

        return $template;

    }
}