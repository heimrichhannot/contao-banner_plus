<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\BannerPlusBundle\Template;


use BugBuster\Banner\BannerImage;
use BugBuster\Banner\BannerLog;
use BugBuster\Banner\BannerSingle;
use Contao\File;
use Contao\FilesModel;
use Contao\Image;
use Contao\Picture;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\BannerPlusBundle\Model\BannerModel;
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;

class SingleBannerTemplate extends BannerSingle
{

    public function getSingleBanner($module_id)
    {
        $this->Template = parent::getSingleBanner($module_id);

        $this->Template->banners = System::getContainer()->get(HtmlType::class)->prepare($this->Template->banners, $this->arrAllBannersBasic);

        if(!is_array($this->Template->banners)) return $this->Template;

        $arrBanners = $this->Template->banners;

        foreach($arrBanners as $i => $arrBanner)
        {
            $objBanner = BannerModel::findByPk($arrBanner['banner_id']);

            if($objBanner === null) continue;

            if (in_array($objBanner->banner_type, HtmlType::BANNER_TYPES)) {
                $arrBanners[$i] = $arrBanner;
                $this->Template->setName($this->banner_template);
            }

            if($objBanner->banner_type != static::BANNER_TYPE_INTERN) continue;

            $this->addImageData('banner_image_left', StringUtil::deserialize($objBanner->banner_imgSize_left), $arrBanner, $objBanner, 'left');
            $this->addImageData('banner_image_right', StringUtil::deserialize($objBanner->banner_imgSize_right), $arrBanner, $objBanner, 'right');

            // Override the default image size
            if ($this->banner_imgSize != '')
            {
                $size = StringUtil::deserialize($this->banner_imgSize);

                try
                {
                    $src = Image::create($arrBanner['src'], $size)->executeResize()->getResizedPath();
                    $picture = Picture::create($arrBanner['src'], $size)->getTemplateData();

                    if ($src !== $arrBanner['src'])
                    {
                        $objFile = new File(rawurldecode($src));
                    }

                    $arrBanner['picture'] = $picture;

                } catch (\Exception $e)
                {
                    System::log('Image "' . $arrBanner['src'] . '" could not be processed: ' . $e->getMessage(), __METHOD__, TL_ERROR);

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
        return $this->Template;
    }

    protected function addImageData($strKey, $arrSize=array(), &$arrBanner, $objBanner, $strSuffix = '')
    {
        if($objBanner->{$strKey} == '') return false;

        if($strSuffix == '')
        {
            $strSuffix = $strKey;
        }

        $objModel = FilesModel::findByUuid($objBanner->{$strKey});

        if($objModel === null || !file_exists(TL_ROOT . '/'. $objModel->path)) return false;

        $objBannerImage = new BannerImage();

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
                'src'    => StringUtil::specialchars(ampersand($singleSRC)),
                'width'  => $arrImageSizenNew[0],
                'height' => $arrImageSizenNew[1],
                'srcset' => StringUtil::specialchars(ampersand($singleSRC))
            );
            $picture['alt']   = StringUtil::specialchars(ampersand($objBanner->banner_name));
            $picture['title'] = StringUtil::specialchars(ampersand($objBanner->banner_comment));
            $picture['class'] = 'banner_image_' . $strSuffix;

            BannerLog::writeLog(__METHOD__ , __LINE__ , 'Orisize Picture ' .$strKey . ': '. print_r($picture,true));
        }
        else
        {
            $singleSRC = Image::get($this->urlEncode($objModel->path), $arrImageSizenNew[0], $arrImageSizenNew[1],'proportional');

            $picture = Picture::create($this->urlEncode($objModel->path), array($arrImageSizenNew[0], $arrImageSizenNew[1], $arrSize[2]))->getTemplateData();
            $picture['alt']   = StringUtil::specialchars(ampersand($objBanner->banner_name));
            $picture['title'] = StringUtil::specialchars(ampersand($objBanner->banner_comment));
            $picture['class'] = 'banner_image_' . $strSuffix;

            BannerLog::writeLog(__METHOD__ , __LINE__ , 'Resize Picture ' .$strKey . ': '. print_r($picture,true));

            $arrImageSize[0] = $arrImageSizenNew[0];
            $arrImageSize[1] = $arrImageSizenNew[1];
            $arrImageSize[3] = ' height="'.$arrImageSizenNew[1].'" width="'.$arrImageSizenNew[0].'"';
        }


        switch ($arrImageSize[2]) {
            case 1:
            case 2:
            case 3:
                $arrBanner['banner_pic_' . $strSuffix] = true;
                $arrBanner['src_' . $strSuffix] =  StringUtil::specialchars(ampersand($singleSRC));
                $arrBanner['picture_' . $strSuffix] = $picture;
                $arrBanner['size_' . $strKey] = $arrImageSize[3];
                break;
        }

    }
}