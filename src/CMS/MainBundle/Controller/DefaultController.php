<?php

namespace CMS\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/{slug}", defaults={"slug" = "asdasd"})
     * @Template()
     */
    public function indexAction($slug)
    {
        $em    = $this->getDoctrine()->getManager();
        $query = $em->getRepository('CMSAdminBundle:Article')->findNewestSql();

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $specialGroup = $em->getRepository('CMSAdminBundle:GroupArticle')->findSpecialSql();

        // parameters to template
        return array('pagination' => $pagination, 'specialGroup' => $specialGroup->getResult());
    }

    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuTopAction()
    {
        $menus = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:GroupArticle')
            ->findAll();

        return array('menus' => $menus);
    }

    /**
     * @Route("/right")
     * @Template()
     */
    public function rightSlideBarAction()
    {
        $specials = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:SpecialGroupArticle')
            ->findAllSortByPositionSql();

        $viewBest = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:Article')
            ->findViewBestSql();

        return array('specials' => $specials->getResult(), 'viewBests' => $viewBest->getResult());
    }
}
