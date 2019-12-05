<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package anwaltverein
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Banner;


use BugBuster\Banner\BannerInsertTag;
use BugBuster\Banner\BannerLogic;
use BugBuster\Banner\BannerSingle;
use Contao\Database;
use Contao\FrontendTemplate;

class ModuleBannerTag extends BannerInsertTag
{

    /**
     * Insert/Update Banner View Stat
     */
    public function setStatViewUpdate()
    {
        if ($this->bannerCheckBot() === true)
        {
            return; //Bot gefunden, wird nicht gezaehlt
        }
        if ($this->checkUserAgent() === true)
        {
            return ; //User Agent Filterung
        }

        // Blocker
        $lastBanner = array_pop($this->arrBannerData);
        $BannerID = $lastBanner['banner_id'];
        $arrCategory = $this->arrCategoryValues;

        if ($BannerID==0)
        { // kein Banner, nichts zu tun
            return;
        }

        // check if banner is visible for user by media query

        /**
         * @todo temporary disabled viewport check. Should be generally replaced by an client only check (without cookie)
         */
//        if ($arrCategory['banner_mediaquery'] != '' && !\HeimrichHannot\MediaQuery\Viewport::matchQuery($arrCategory['banner_mediaquery']))
//        {
//            return;
//        }

        if ( $this->getStatViewUpdateBlockerId($BannerID) === true )
        {
            // Eintrag innerhalb der Blockzeit
            return; // blocken, nicht zählen, raus hier
        }
        else
        {
            // nichts geblockt, also blocken fürs den nächsten Aufrufs
            $this->setStatViewUpdateBlockerId($BannerID);
        }

        //Zählung, Insert
        $arrSet = array
        (
            'id' => $BannerID,
            'tstamp' => time(),
            'banner_views' => 1
        );
        $objInsert = \Database::getInstance()->prepare("INSERT IGNORE INTO tl_banner_stat %s")
            ->set($arrSet)
            ->execute();
        if ($objInsert->insertId == 0)
        {
            //Zählung, Update
            \Database::getInstance()->prepare("UPDATE
                                                    `tl_banner_stat`
                                               SET
                                                    `tstamp`=?
                                                  , `banner_views` = `banner_views`+1
                                               WHEREsl
                                                    `id`=?")
                ->execute(time(), $BannerID);
        }
    }

    public function compileSlickNewsListHook(&$objTemplate, $objModule, $objModel)
    {
        $this->getModuleData($objModule->id);

        if($this->bannerHelperInit() === false)
        {
            $this->log('Problem in Bannerhelper::bannerHelperInit', 'HeimrichHannot\Banner\Hooks compileSlickNewsListHook', TL_ERROR);
            return false;
        }

        $this->typePrefix = 'mod_';
        $this->class = 'mod_banner';

        $this->article_class = $this->class[1];
        $this->article_cssID = $this->cssID[0];
        $this->article_style = $this->style;
        $this->banner_imgSize = $objModel->imgSize;

        if ($this->statusBannerFrontendGroupView === false)
        {
            // Eingeloggter FE Nutzer darf nichts sehen, falsche Gruppe
            return false;
        }


        $this->Template = new FrontendTemplate($this->strTemplate);

        if ($this->statusAllBannersBasic === false)
        {
            //keine Banner vorhanden in der Kategorie
            //default Banner holen
            //kein default Banner, ausblenden wenn leer?
            $objBannerSingle = new BannerSingle($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
            $this->Template = $objBannerSingle->getDefaultBanner($this->banner_hideempty);
        }

        //OK, Banner vorhanden, dann weiter
        //BannerSeen vorhanden? Dann beachten.
        if ( count(self::$arrBannerSeen) )
        {
            //$arrAllBannersBasic dezimieren um die bereits angezeigten
            foreach (self::$arrBannerSeen as $BannerSeenID)
            {
                if (array_key_exists($BannerSeenID,$this->arrAllBannersBasic))
                {
                    unset($this->arrAllBannersBasic[$BannerSeenID]);
                };
            }
            //noch Banner übrig?
            if ( count($this->arrAllBannersBasic) == 0 )
            {
                //default Banner holen
                //kein default Banner, ausblenden wenn leer?
                $this->setCssClassIdStyle();
            }
        }

        //OK, noch Banner übrig, weiter gehts
        //Single Banner?
        if ($this->arrCategoryValues['banner_numbers'] == 1)
        {
            $objBannerLogic = new BannerLogic();
            if ($objBannerLogic->getSetFirstView($this->banner_firstview,$this->banner_categories,$this->module_id) === true)
            {
                $objBannerSingle = new BannerSingle($this->arrCategoryValues, $this->banner_template, $this->strTemplate, $this->Template, $this->arrAllBannersBasic);
                $this->Template = $objBannerSingle->getSingleBannerFirst($this->module_id);
            }
            else if(!empty($this->arrAllBannersBasic))
            {
                //single banner
                $this->getSingleBanner();
            }
        }
        else
        {
            //multi banner
            $this->getMultiBanner();
        }

        if(is_array($this->Template->banners))
        {
            $style = new SliderDisplayFormat($objTemplate->articles, $this->Template->banners, $this->arrAllBannersBasic, $this);
            $objTemplate->articles = $style->format($objModule->banner_plus_displayFormat);
        }
    }

    public function renderBanner(array $banner)
    {
        $this->setCssClassIdStyle();
        $this->Template->banners = array($banner);
        return $this->Template->parse();
    }

    /**
     * Wrapper for backward compatibility
     *
     * @param integer $moduleId
     * @return boolean
     */
    protected function getModuleData($moduleId)
    {
        $this->module_id = $moduleId; //for RandomBlocker Session
        //DEBUG log_message('getModuleData Banner Modul ID:'.$moduleId,'Banner.log');
        $objBannerModule = Database::getInstance()->prepare("SELECT
                                                                    banner_hideempty,
                                                        	        banner_firstview,
                                                        	        banner_categories,
                                                        	        banner_template,
                                                        	        banner_redirect,
                                                        	        banner_useragent,
                                                                    cssID,
                                                                    headline
                                                                FROM
                                                                    tl_module
                                                                WHERE
                                                                    id=? AND banner_categories !=''")
            ->execute($moduleId);
        if ($objBannerModule->numRows == 0)
        {
            return false;
        }
        $this->banner_hideempty  = $objBannerModule->banner_hideempty;
        $this->banner_firstview  = $objBannerModule->banner_firstview;
        $this->banner_categories = $objBannerModule->banner_categories;
        $this->banner_template   = $objBannerModule->banner_template;
        $this->banner_redirect   = $objBannerModule->banner_redirect;
        $this->banner_useragent  = $objBannerModule->banner_useragent;
        $this->cssID             = $objBannerModule->cssID;
        $this->headline          = $objBannerModule->headline;
        return true;
    }

    /**
     * Set Category Values in $this->arrCategoryValues over tl_banner_category
     *
     * @return boolean    true = OK | false = we have a problem
     */
    protected function getSetCategoryValues()
    {
        //DEBUG log_message('getSetCategoryValues banner_categories:'.$this->banner_categories,'Banner.log');
        //$this->banner_categories is now an ID, but the name is backward compatible
        if ( !isset($this->banner_categories) || !is_numeric($this->banner_categories) )
        {
            $this->log($GLOBALS['TL_LANG']['tl_banner']['banner_cat_not_found'], 'ModulBanner Compile', 'ERROR');
            $this->arrCategoryValues = false;
            return false;
        }
        $objBannerCategory = Database::getInstance()->prepare("SELECT
                                                                    *
                                                                FROM
                                                                    tl_banner_category
                                                                WHERE
                                                                    id=?")
            ->execute($this->banner_categories);
        if ($objBannerCategory->numRows == 0)
        {
            $this->log($GLOBALS['TL_LANG']['tl_banner']['banner_cat_not_found'], 'ModulBanner Compile', 'ERROR');
            $this->arrCategoryValues = false;
            return false;
        }
        $arrGroup = deserialize($objBannerCategory->banner_groups);
        //Pfad+Dateiname holen ueber UUID (findByPk leitet um auf findByUuid)
        $objFile = \FilesModel::findByPk($objBannerCategory->banner_default_image);
        $this->arrCategoryValues = array(
            'id'                    => $objBannerCategory->id,
            'banner_default'		=> $objBannerCategory->banner_default,
            'banner_default_name'	=> $objBannerCategory->banner_default_name,
            'banner_default_image'	=> $objFile->path,
            'banner_default_url'	=> $objBannerCategory->banner_default_url,
            'banner_default_target'	=> $objBannerCategory->banner_default_target,
            'banner_numbers'		=> $objBannerCategory->banner_numbers == '' ? 0 : $objBannerCategory->banner_numbers, //0:single,1:multi,see banner_limit
            'banner_random'			=> $objBannerCategory->banner_random,
            'banner_limit'			=> $objBannerCategory->banner_limit, // 0:all, others = max
            'banner_protected'		=> $objBannerCategory->banner_protected,
            'banner_mediaquery'		=> $objBannerCategory->banner_mediaquery, // add media query
            'banner_group'			=> $arrGroup[0]
        );
        //DEBUG log_message('getSetCategoryValues arrCategoryValues:'.print_r($this->arrCategoryValues,true),'Banner.log');
        return true;
    }

    /**
     * Overwrite BugBuster\Banner\BannerHelper::getSetAllBannerForCategory()
     * to provide custom logic
     * - add page filter
     *
     * @return bool
     */
    protected function getSetAllBannerForCategory()
    {
        $this->arrAllBannersBasic = array();
        //wenn mit der definierte Kategorie ID keine Daten gefunden wurden
        //macht Suche nach Banner kein Sinn
        if ($this->arrCategoryValues === false)
        {
            return false;
        }
        //Domain Name ermitteln
        $http_host = \Environment::get('host');
        //aktueller Zeitstempel
        $intTime = time();

        //alle gültigen aktiven Banner,
        //ohne Beachtung der Gewichtung,
        //mit Beachtung der Domain
        //sortiert nach "sorting"
        //nur Basic Felder `id`, `banner_weighting`
        $objBanners = \Database::getInstance()
            ->prepare("SELECT
                                        TLB.*
                                   FROM
                                        tl_banner AS TLB
                                   LEFT JOIN
                                        tl_banner_category ON tl_banner_category.id=TLB.pid
                                   LEFT OUTER JOIN
                                        tl_banner_stat AS TLS ON TLB.id=TLS.id
                                   WHERE
                                        pid=?
                                   AND (
                                           (TLB.banner_until=?)
		                                OR (TLB.banner_until=1 AND TLB.banner_views_until>TLS.banner_views)
                                        OR (TLB.banner_until=1 AND TLB.banner_views_until=?)
                                        OR (TLB.banner_until=1 AND TLS.banner_views is NULL)
                                       )
                                   AND (
                                           (TLB.banner_until=?)
                                        OR (TLB.banner_until=1 AND TLB.banner_clicks_until>TLS.banner_clicks)
                                        OR (TLB.banner_until=1 AND TLB.banner_clicks_until=?)
                                        OR (TLB.banner_until=1 AND TLS.banner_clicks is NULL)
                                       )
                                   AND
                                        TLB.banner_published =?
                                   AND
                                       (TLB.banner_start=? OR TLB.banner_start<=?)
                                   AND
                                       (TLB.banner_stop=? OR TLB.banner_stop>=?)
                                   ORDER BY TLB.`sorting`"
            )
            ->execute($this->banner_categories
                , '', ''
                , '', ''
                , 1
                , '', $intTime, '', $intTime
                , '', $http_host);

        while ($objBanners->next())
        {
            if(!$this->isVisible($objBanners)) continue;

            $this->arrAllBannersBasic[$objBanners->id] = $objBanners->banner_weighting;
        }
        //DEBUG log_message('getSetAllBannerForCategory arrAllBannersBasic:'.print_r($this->arrAllBannersBasic,true),'Banner.log');
        return (bool)$this->arrAllBannersBasic; //false bei leerem array, sonst true
    }


    protected function isVisible($objBanners)
    {
        global $objPage;

        $arrPages = deserialize($objBanners->pages);

        /**
         * Filter out pages
         * (exclude == display module not on this page)
         * (include == display module only on this page)
         */
        if(is_array($arrPages) && count($arrPages) > 0)
        {
            // add nested pages to the filter
            if($objBanners->addPageDepth)
            {
                $arrPages = array_merge($arrPages, \Database::getInstance()->getChildRecords($arrPages, 'tl_page'));
            }


            $check = ($objBanners->addVisibility == 'exclude') ? true : false;

            if(in_array($objPage->id, $arrPages) == $check)
            {
                return false;
            }
        }

        return true;
    }

    protected function getSingleBanner()
    {
        $blnParent = parent::getSingleBanner();

        if(!is_array($this->Template->banners)) return $blnParent;

        $arrBanners = $this->Template->banners;

        foreach($arrBanners as $i => $arrBanner)
        {
            $objBanner = BannerModel::findByPk($arrBanner['banner_id']);

            if($objBanner === null) continue;

            if($objBanner->banner_type != static::BANNER_TYPE_INTERN) continue;

            $this->addImageData('banner_image_left', deserialize($objBanner->banner_imgSize_left), $arrBanner, $objBanner, 'left');
            $this->addImageData('banner_image_right', deserialize($objBanner->banner_imgSize_right), $arrBanner, $objBanner, 'right');

            // Override the default image size
            if ($this->banner_imgSize != '')
            {
                $size = deserialize($this->banner_imgSize);

                try
                {
                    $src = \Image::create($arrBanner['src'], $size)->executeResize()->getResizedPath();
                    $picture = \Picture::create($arrBanner['src'], $size)->getTemplateData();

                    if ($src !== $arrBanner['src'])
                    {
                        $objFile = new \File(rawurldecode($src), true);
                    }

                    $arrBanner['picture'] = $picture;

                } catch (\Exception $e)
                {
                    \System::log('Image "' . $arrBanner['src'] . '" could not be processed: ' . $e->getMessage(), __METHOD__, TL_ERROR);

                    $src = '';
                    $picture = array('img'=>array('src'=>'', 'srcset'=>''), 'sources'=>array());
                }
            }

            $arrBanner['banner_animation'] = $objBanner->banner_animation;
            if ($objBanner->banner_animation) {
                $arrBanner['banner_animation'] = $objBanner->banner_animation;
            } else {
                $arrBanner['banner_animation'] = '';
            }

            $arrBanners[$i] = $arrBanner;
        }

        $this->Template->banners = $arrBanners;
    }

    protected function getMultiBanner()
    {
        $blnParent = parent::getMultiBanner();

        if(!is_array($this->Template->banners)) return $blnParent;

        $arrBanners = $this->Template->banners;

        foreach($arrBanners as $i => $arrBanner)
        {
            $objBanner = \BannerModel::findByPk($arrBanner['banner_id']);

            if($objBanner === null) continue;

            if($objBanner->banner_type != static::BANNER_TYPE_INTERN) continue;

            $arrBanner['banner_animation'] = $objBanner->banner_animation;
            if ($objBanner->banner_animation) {
                $arrBanner['banner_animation'] = $objBanner->banner_animation;
            } else {
                $arrBanner['banner_animation'] = '';
            }

            $arrBanners[$i] = $arrBanner;
        }

        $this->Template->banners = $arrBanners;
    }

    protected function addImageData($strKey, $arrSize=array(), &$arrBanner, $objBanner, $strSuffix = '')
    {
        if($objBanner->{$strKey} == '') return false;

        if($strSuffix == '')
        {
            $strSuffix = $strKey;
        }

        $objModel = \FilesModel::findByUuid($objBanner->{$strKey});

        if($objModel === null || !file_exists(TL_ROOT . '/'. $objModel->path)) return false;

        $objBannerImage = new \BugBuster\Banner\BannerImage();

        $arrImageSize = $objBannerImage->getBannerImageSize($objModel->path, $objBanner->banner_type);

        $arrImageSizenNew = $objBannerImage->getBannerImageSizeNew($arrImageSize[0],$arrImageSize[1],$arrSize[0],$arrSize[1]);

        $singleSRC = $objModel->path;

        //if oriSize = true, oder bei GIF - 1/SWF - 4/SWC - 13 = use original path
        if ($arrImageSizenNew[2] === true || in_array($arrImageSize[2], array(1,4,13)))
        {
            $arrImageSize[0] = $arrImageSizenNew[0];
            $arrImageSize[1] = $arrImageSizenNew[1];
            $arrImageSize[3] = ' height="'.$arrImageSizenNew[1].'" width="'.$arrImageSizenNew[0].'"';

            //fake the Picture::create
            $picture['img']   = array
            (
                'src'    => specialchars(ampersand($singleSRC)),
                'width'  => $arrImageSizenNew[0],
                'height' => $arrImageSizenNew[1],
                'srcset' => specialchars(ampersand($singleSRC))
            );
            $picture['alt']   = specialchars(ampersand($objBanner->banner_name));
            $picture['title'] = specialchars(ampersand($objBanner->banner_comment));
            $picture['class'] = 'banner_image_' . $strSuffix;

            \BugBuster\Banner\ModuleBannerLog::writeLog(__METHOD__ , __LINE__ , 'Orisize Picture ' .$strKey . ': '. print_r($picture,true));
        }
        else
        {
            $singleSRC = \Image::get($this->urlEncode($objModel->path), $arrImageSizenNew[0], $arrImageSizenNew[1],'proportional');

            $picture = \Picture::create($this->urlEncode($objModel->path), array($arrImageSizenNew[0], $arrImageSizenNew[1], $arrSize[2]))->getTemplateData();
            $picture['alt']   = specialchars(ampersand($objBanner->banner_name));
            $picture['title'] = specialchars(ampersand($objBanner->banner_comment));
            $picture['class'] = 'banner_image_' . $strSuffix;

            \BugBuster\Banner\ModuleBannerLog::writeLog(__METHOD__ , __LINE__ , 'Resize Picture ' .$strKey . ': '. print_r($picture,true));

            $arrImageSize[0] = $arrImageSizenNew[0];
            $arrImageSize[1] = $arrImageSizenNew[1];
            $arrImageSize[3] = ' height="'.$arrImageSizenNew[1].'" width="'.$arrImageSizenNew[0].'"';
        }


        switch ($arrImageSize[2]) {
            case 1:
            case 2:
            case 3:
                $arrBanner['banner_pic_' . $strSuffix] = true;
                $arrBanner['src_' . $strSuffix] =  specialchars(ampersand($singleSRC));
                $arrBanner['picture_' . $strSuffix] = $picture;
                $arrBanner['size_' . $strKey] = $arrImageSize[3];
                break;
        }

    }
}