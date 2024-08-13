<?php
/**
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\BannerPlusBundle\DataContainer;

use BugBuster\Banner\DcaBanner;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\StringUtil;
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;

class BannerContainer
{

    #[AsCallback(table: 'tl_banner', target: 'config.onload')]
    public function onConfigLoadCallback(DataContainer|null $dc): void
    {
        $GLOBALS['TL_JAVASCRIPT']['banner_plus-html-banner'] = 'bundles/contaobannerplus/assets/banner_plus-html-banner.js';
    }

    /**
     * List banner record
     *
     * @param object $row
     */
    public function listBanner(array $row): string
    {
        switch ($row['banner_type'])
        {
            case HtmlType::BANNER_TYPE_HTML_INTERN:
            case HtmlType::BANNER_TYPE_HTML_EXTERN:
                return $this->listBannerHtml($row);
                break;
            default :
                $dcaBanner = new DcaBanner();
                return $dcaBanner->listBanner($row);
                break;
        }

    }

    public function listBannerHtml(array $banner): string
    {

        $bannerUrl = FilesModel::findByUuid(StringUtil::binToUuid($banner['banner_html']))->path;

        $html = '<div class="mod_banner_be">
                <div class="name">
                    <iframe src="'.$bannerUrl.'" class="iframe-resized"></iframe>
                </div>
                <div class="right">
                    <div class="left">
                        <div class="published_head">'.$GLOBALS['TL_LANG']['tl_banner']['banner_published'][0].'</div>
                        <div class="published_data">'.($banner['banner_published'] =='' ? $GLOBALS['TL_LANG']['tl_banner']['tl_be_no'] : $GLOBALS['TL_LANG']['tl_banner']['tl_be_yes']).' </div>
                    </div>
                    <div class="left">
                        <div class="date_head">'.$GLOBALS['TL_LANG']['tl_banner']['banner_type'][0].'</div>
                        <div class="date_data">'.$GLOBALS['TL_LANG']['tl_banner']['source_intern'] .'</div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="left">
                        <div class="date_head">'.$GLOBALS['TL_LANG']['tl_banner']['tl_be_start'].'</div>
                        <div class="date_data">' . ($banner['banner_start']=='' ? $GLOBALS['TL_LANG']['tl_banner']['tl_be_not_defined_start'] : date($GLOBALS['TL_CONFIG']['datimFormat'], $banner['banner_start'])) . '</div>
                    </div>
                    <div class="left">
                        <div class="date_head">'.$GLOBALS['TL_LANG']['tl_banner']['tl_be_stop'].'</div>
                        <div class="date_data">' . ($banner['banner_stop'] =='' ? $GLOBALS['TL_LANG']['tl_banner']['tl_be_not_defined_stop'] : date($GLOBALS['TL_CONFIG']['datimFormat'], $banner['banner_stop'])) . '</div>
                    </div>
                    <div style="clear:both;"></div>
                    <div class="left">
                        <div class="date_head">'.$GLOBALS['TL_LANG']['tl_banner']['tl_be_max_views'].'</div>
                        <div class="date_data">' . ($banner['banner_views_until']=='' ? $GLOBALS['TL_LANG']['tl_banner']['tl_be_not_defined_max'] : $banner['banner_views_until']) . '</div>
                    </div>
                    <div class="left">
                        <div class="date_head">'.$GLOBALS['TL_LANG']['tl_banner']['tl_be_max_clicks'].'</div>
                        <div class="date_data">' . ($banner['banner_clicks_until'] =='' ? $GLOBALS['TL_LANG']['tl_banner']['tl_be_not_defined_max'] : $banner['banner_clicks_until']) . '</div>
                    </div>
                    <div style="clear:both;"></div>
                </div>';

        $key = $banner['banner_published'] ? 'published' : 'unpublished';
        $style = 'style="font-size:11px;margin-bottom:10px;"';
        $output_h = '<div class="cte_type ' . $key . '" ' . $style . '><strong>' . StringUtil::specialchars(StringUtil::ampersand($banner['banner_name'])) . '</strong></div>';

        return $output_h . $html;
    }
}
