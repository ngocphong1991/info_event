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
     * @Route("/{slug}")
     * @Template()
     */
    public function routAction($slug)
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
    public function menuAction($position)
    {
        $menus = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:GroupArticle')
            ->findAll();

        return array('menus' => $menus, 'position' => $position);
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
