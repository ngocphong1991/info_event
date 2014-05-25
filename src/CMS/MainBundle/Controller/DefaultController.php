<?php

namespace CMS\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $em    = $this->getDoctrine()->getManager();

        //Setup slider group article
        $specialGroup = $em->getRepository('CMSAdminBundle:GroupArticle')->findSpecialSql()->getResult();
        $listSpecial = array();
        $listArticleSpecial = array();
        foreach($specialGroup as $special){
            $articles = $em->getRepository('CMSAdminBundle:Article')->findNewestForSliderSql($special->getId())->getResult();
            $idSpecial = $special->getId();
            $listSpecial[$idSpecial]['id'] = $idSpecial;
            $listSpecial[$idSpecial]['url'] = $special->getUrl();
            foreach($articles as $art){
                $listSpecial[$idSpecial]['articles'][] = $art;
                $listArticleSpecial[] = $art->getId();
            }
        }
        $listArticleSpecial = implode(',',$listArticleSpecial);

        // get article for index page
        if($listArticleSpecial) $query = $em->getRepository('CMSAdminBundle:Article')->findNewestNotSliderSql($listArticleSpecial);
        else $query = $em->getRepository('CMSAdminBundle:Article')->findNewestSql();

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        $pagination->setTemplate('CMSMainBundle:Default:pager.html.twig');

        // parameters to template
        return array('pagination' => $pagination, 'specialGroup' => $specialGroup, 'sliderGroups' => $listSpecial);
    }

    /**
     * @Route("/chuyen-muc/{slug}")
     * @Template()
     */
    public function groupAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $group = $em->getRepository('CMSAdminBundle:GroupArticle')->findOneBy(
            array('url' => $slug, 'isActive' => 1)
        );

        $query = $em->getRepository('CMSAdminBundle:Article')->findByGroupSql(
            $group->getId()
        );

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        $pagination->setTemplate('CMSMainBundle:Default:pager.html.twig');

        return array('pagination' => $pagination, 'groupName' => $group->getName(), 'idGroup' => $group->getId() );

    }

    /**
     * @Route("/chuyen-muc/{slugGroup}/{slugArticle}")
     * @Template()
     */
    public function articleAction($slugGroup, $slugArticle)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('CMSAdminBundle:Article')->findOneBy(
            array('url' => $slugArticle, 'isActive' => 1)
        );

        $related = array();
        $idGroup = $article->getGroupArticle()->getId();

        if($idGroup)
            $related = $em->getRepository('CMSAdminBundle:Article')->findRelatedSql($idGroup, $article->getId());

        $specials = $article->getSpecialGroupArticle();
        $listSpecial = array();
        if(count($specials)){
            foreach($specials as $special){
                $listSpecial[] = $special->getId();
            }
        }

        $article->setViews($article->getViews()+1);
        $em->flush();
        return $this->render('CMSMainBundle:Default:article.html.twig',
            array('article' => $article, 'listSpecial' => $listSpecial, 'related' => $related->getResult())
        );
    }

    /**
     * @Route("/tim-kiem", name="cms_main_search")
     * @Template()
     */
    public function searchAction()
    {
        $keyword = $this->get('request')->query->get('tu-khoa', '');

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('CMSAdminBundle:Article')->findByKeywordFrontEndSql(
            $keyword
        );

        $result = count($query->getResult());
        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        $pagination->setTemplate('CMSMainBundle:Default:pager.html.twig');

        return array('pagination' => $pagination, 'result' => $result);

    }

    /**
     * @Route("/{slug}")
     * @Template()
     */
    public function cmsAction($slug){
        if($slug == 'admin') return $this->redirect($this->generateUrl('article'));

        $em = $this->getDoctrine()->getManager();
        $cms = $em->getRepository('CMSAdminBundle:CmsPage')->findOneBy(
            array('url' => $slug, 'isActive' => 1)
        );

        return array('cms' => $cms);
    }

    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuAction($position)
    {
        $menus = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:GroupArticle')
            ->findAll();

        $cms = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:CmsPage')
            ->findAll();

        return array('menus' => $menus, 'cms' => $cms, 'position' => $position);
    }

    /**
     * @Route("/right")
     * @Template()
     */
    public function rightSlideBarAction($cpc, $listSpecial)
    {
        $specials = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:SpecialGroupArticle')
            ->findAllSortByPositionSql();

        $viewBest = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:Article')
            ->findViewBestSql();

        return array(
                'listSpecial' => $listSpecial ? $listSpecial : array(),
                'cpc' => isset($cpc) && $cpc ? $cpc : false,
                'specials' => $specials->getResult(),
                'viewBests' => $viewBest->getResult()
        );
    }
}
