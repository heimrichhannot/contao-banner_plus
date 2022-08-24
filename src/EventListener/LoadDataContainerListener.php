<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\EventListener;


use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\ModuleLoader;
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;

/**
 * @Hook("loadDataContainer")
 */
class LoadDataContainerListener
{
    public function __invoke(string $table): void
    {
        switch ($table) {
            case 'tl_module':
                $this->updateModuleDataContainer();
                break;
            case 'tl_banner':
                $this->updateBannerDataContainer($table);
                break;
        }
    }

    public function updateModuleDataContainer(): void
    {
        $dc = &$GLOBALS['TL_DCA']['tl_module'];
        $active = ModuleLoader::getActive();
        if (in_array('slick', $active) || in_array('HeimrichHannotContaoSlickBundle', $active))
        {
            $dc['palettes']['slick_newslist'] = str_replace(
                'skipFirst;',
                'skipFirst;{banner_legend},banner_hideempty,banner_firstview,banner_categories,banner_template,banner_plus_displayFormat,banner_redirect,guests,protected,banner_useragent;',
                $dc['palettes']['slick_newslist']
            );
        }
    }

    public function updateBannerDataContainer(string $table): void
    {
        $dca = &$GLOBALS['TL_DCA'][$table];
        $options = HtmlType::BANNER_TYPES;
        $dca['fields']['banner_type']['options'] = array_merge(is_array($dca['fields']['banner_type']['options']) ? $dca['fields']['banner_type']['options'] : [], $options);

        $fields = [
            'banner_html' => [
                'label'                   => &$GLOBALS['TL_LANG']['tl_banner']['banner_html'],
                'explanation'	          => 'banner_html',
                'inputType'               => 'fileTree',
                'eval'                    => [
                    'mandatory'=>true,
                    'files'=>true,
                    'filesOnly'=>true,
                    'fieldType'=>'radio',
                    'extensions'=>'html,html5',
                    'maxlength'=>255,
                    'helpwizard'=>true
                ],
                'sql'                     => "binary(16) NULL",
            ]
        ];

        $dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $fields);

        $dca['palettes'][HtmlType::BANNER_TYPE_HTML_INTERN] = 'banner_type;{title_legend},banner_name,banner_weighting;{destination_legend},banner_url,banner_jumpTo,banner_target;{image_legend},banner_html;{comment_legend},banner_comment;{filter_legend:hide},addVisibility,pages,addPageDepth;{expert_legend:hide},banner_cssid;{publish_legend},banner_published,banner_start,banner_stop,banner_until';
        $dca['palettes'][HtmlType::BANNER_TYPE_HTML_EXTERN] = 'banner_type;{title_legend},banner_name,banner_weighting;{destination_legend},banner_url,banner_jumpTo,banner_target;{comment_legend},banner_comment;{filter_legend:hide},addVisibility,pages,addPageDepth;{expert_legend:hide},banner_cssid;{publish_legend},banner_published,banner_start,banner_stop,banner_until';
    }
}