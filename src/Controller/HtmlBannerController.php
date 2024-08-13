<?php

namespace HeimrichHannot\BannerPlusBundle\Controller;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\StringUtil;
use HeimrichHannot\BannerPlusBundle\Model\BannerModel;
use HeimrichHannot\BannerPlusBundle\Type\HtmlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HtmlBannerController extends AbstractController
{
    public function __construct(
        public readonly ParameterBagInterface $parameterBag,
        public readonly ContaoFramework $framework,
    )
    {
    }

    #[Route('/bannerplus/html/{id}', name: 'bannerplus_html_banner')]
    public function display(Request $request, int $id): Response
    {
        $this->framework->initialize();

        $objBanner = BannerModel::findByPk($id);
        if (null === $objBanner) {
            return new Response('', 404);
        }

        if (!in_array($objBanner->banner_type, HtmlType::BANNER_TYPES)) {
            return new Response('', 404);
        }

        $root = $this->parameterBag->get('kernel.project_dir');
        $bannerUrl = FilesModel::findByUuid(StringUtil::binToUuid($objBanner->banner_html))->path;
        $fs = new Filesystem();

        if (!$fs->exists($root . '/' . $bannerUrl)) {
            return new Response('', 404);
        }

        $banner_html = file_get_contents($root . '/' . $bannerUrl);

        $document = new \DOMDocument();
        $document->loadHTML($banner_html);

        $crawler = new Crawler($banner_html);
        $base = $crawler->filter('head base');
        if ($base->count() < 1) {
            $base = $crawler->getNode(0)->ownerDocument->createElement('base');
            $path = pathinfo($bannerUrl, PATHINFO_DIRNAME);
            $base->setAttribute('href', $request->getSchemeAndHttpHost().'/'.trim($path, '/').'/' );
            $child = $crawler->filter(selector: 'head')->getNode(0)->firstChild;
            if (!$child) {
                $crawler->filter('head')->getNode(0)->appendChild($base);
            } else {
                $crawler->filter('head')->getNode(0)->insertBefore($base, $child);
            }
        }

        return new Response($crawler->html());
    }
}