<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\Helper;


use Contao\FrontendTemplate;
use HeimrichHannot\BannerPlusBundle\DataContainer\ModuleContainer;
use HeimrichHannot\BannerPlusBundle\Template\SlickBannerTemplate;

class SliderDisplayFormatHelper
{
    /**
     * @var array
     */
    private $articles;
    /**
     * @var array
     */
    private $banners;
    /**
     * @var array
     */
    private $bannerWeights;
    /**
     * @var FrontendTemplate
     */
    private $template;

    protected $bannersWeightSorted = [];

    /**
     * SliderDisplayFormat constructor.
     */
    public function __construct(array $articles, array $banner, array $bannerWeights, $template)
    {

        $this->articles      = $articles;
        $this->banners       = $banner;
        $this->bannerWeights = $bannerWeights;
        $this->template        = $template;
    }

    /**
     * Returns a formated list of articles and banners as articles array
     *
     * @param string $displayFormat
     * @return array
     */
    public function format(string $displayFormat = "")
    {
        switch ($displayFormat)
        {
            case ModuleContainer::DISPLAY_ROTATORY:
                return $this->displayRotatory();
            case ModuleContainer::DISPLAY_DEFAULT:
            default:
                return $this->displayDefault();
        }
    }

    protected function displayDefault()
    {
        $articles = $this->articles;
        foreach ($this->banners as $banner)
        {
            $weight = $this->bannerWeights[$banner['banner_id']];

            switch ($weight)
            {
                // highest priority -> prepend
                case 1:
                    array_unshift($articles, $this->renderBanner($banner));
                    break;
                // normal priority -> middle
                case 2:
                    array_insert($articles, ceil(count($articles) / 2), [$this->renderBanner($banner)]);
                    break;
                // lowest priority -> append
                case 3:
                    array_insert($articles, count($articles), [$this->renderBanner($banner)]);
                    break;
            }
        }
        return $articles;
    }

    protected function displayRotatory()
    {
        $sliderContent = [];
        $sortedBanners = $this->sortBannersByWeight();
        $articles = $this->articles;
        $priority = 1;
        while (true)
        {
            $sliderContent[] = array_shift($articles);
            if (empty($articles))
            {
                break;
            }
            // Skip empty priority values
            while ($priority <= 3 &&  empty($sortedBanners[$priority]))
            {
                $priority++;
            }
            if ($priority > 3)
            {
                continue;
            }
            $banner                = array_shift($sortedBanners[$priority]);
            $sliderContent[] = $this->renderBanner($banner);
        }
        return $sliderContent;
    }

    /**
     * Return banners sorted by their weights
     *
     * @return array
     */
    protected function sortBannersByWeight()
    {
        if (!empty($this->bannersWeightSorted))
        {
            return $this->bannersWeightSorted;
        }

        $bannersWeightSorted = [
            1 => [],
            2 => [],
            3 => []
        ];

        foreach ($this->banners as $banner)
        {
            $weight = $this->bannerWeights[$banner['banner_id']];
            $banner['weight'] = $weight;
            $bannersWeightSorted[$weight][] = $banner;
        }
        $this->bannersWeightSorted = $bannersWeightSorted;
        return $bannersWeightSorted;
    }

    protected function renderBanner($banner)
    {
        $this->template->banners = array($banner);
        return $this->template->parse();
    }


}