<?php

namespace CMS\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CMS\AdminBundle\Api\SaveLogsApi;

class DefaultController extends Controller
{
    public $logs;

    public function __construct(){
        $this->logs = new SaveLogsApi();
    }

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

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'visit');

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

        // get article
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

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'visit');

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

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'visit');

        return $this->render('CMSMainBundle:Default:article.html.twig',
            array('article' => $article, 'listSpecial' => $listSpecial, 'related' => $related->getResult())
        );
    }

    /**
     * @Route("/print/{slugGroup}/{slugArticle}")
     * @Template()
     */
    public function printAction($slugGroup, $slugArticle)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('CMSAdminBundle:Article')->findOneBy(
            array('url' => $slugArticle, 'isActive' => 1)
        );

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'print');

        return array('article' => $article);
    }

    /**
     * @Route("/chuyen-muc-dac-biet/{id}")
     * @Template()
     */
    public function specialAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $special = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->find($id);

        if($special){
            $article = $em->getRepository('CMSAdminBundle:Article')->findBySpecialGroupSql($id);
            $name = $special->getName();
        }else{
            $name = 'Not found';
            $article =array();
        }

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $article,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        $pagination->setTemplate('CMSMainBundle:Default:pager.html.twig');

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'visit special');

        return array('pagination' => $pagination, 'name' => $name);
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

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'search');

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

        //save log
        $this->logs->save($em, $this->getRequest()->getUri(), 'visit');

        return array('cms' => $cms);
    }

    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuAction($position)
    {
        if($position == 'top'){
            $menuTop = $this->getDoctrine()
                ->getRepository('CMSAdminBundle:GroupArticle')
                ->findMenuTopSql()->getResult();

            return array('menuTop' => $menuTop, 'position' => $position);
        }

        if($position == 'bot'){

            $menuBot = $this->getDoctrine()
                ->getRepository('CMSAdminBundle:GroupArticle')
                ->findMenuBotSql()->getResult();

            $cms = $this->getDoctrine()
                ->getRepository('CMSAdminBundle:CmsPage')
                ->findAll();

            return array('menuBot' => $menuBot, 'cms' => $cms, 'position' => $position);
        }

        return array();
    }

    // define position value of advertise
    const POSITION_TOP = 1;
    const POSITION_RIGHT = 0;

    // get banner for advertise from slug of url
    private function getAdvertiseFromSlug($slug = array(), $position){

        $advertise = null;

        $em = $this->getDoctrine()->getManager();
        $path = $slug[Count($slug)-1];

        //is home page
        if($path == ''){
            $advertise = $em->getRepository('CMSAdminBundle:Advertise')->findHomeAdvertiseSql($position)->getOneOrNullResult();
            if($advertise) return $advertise;
            else{
                $advertise = $em->getRepository('CMSAdminBundle:Advertise')->findGlobalSql($position)->getOneOrNullResult();
                return $advertise;
            }

        }

        //is article detail page
        $article = $em->getRepository('CMSAdminBundle:Article')->findOneBy(
            array('url' => $path, 'isActive' => 1)
        );

        if($article){
            $advertise = $em->getRepository('CMSAdminBundle:Advertise')->findByGroupSql(
                $article->getGroupArticle()->getId(), $position
            )->getOneOrNullResult();

            if($advertise) return $advertise;
        }

        //is group article page
        $group = $em->getRepository('CMSAdminBundle:GroupArticle')->findOneBy(
            array('url' => $path, 'isActive' => 1)
        );

        if($group){
            $advertise = $em->getRepository('CMSAdminBundle:Advertise')->findByGroupSql(
                $group->getId(), $position
            )->getOneOrNullResult();

            if($advertise) return $advertise;
        }

        //is global advertise
        $advertise = $em->getRepository('CMSAdminBundle:Advertise')->findGlobalSql($position)->getOneOrNullResult();

        return $advertise;
    }


    /**
     * @Route("/banner-top")
     * @Template()
     */
    public function bannerTopAction($slug)
    {
        $advertise = null;

        if($slug = explode('/', $slug))
            $advertise = $this->getAdvertiseFromSlug($slug, $this::POSITION_TOP);

        if($advertise){
            $used = $advertise->getCpc()* $advertise->getClick() + round($advertise->getViews()/1000)*$advertise->getCpm();
            if($used >= $advertise->getBudget()){
                $advertise = null;
            }else{
                $advertise->setViews($advertise->getViews()+1);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return array('advertise' => $advertise);
    }

    /**
     * @Route("/banner-right")
     * @Template()
     */
    public function bannerRightAction($slug)
    {

        $advertise = null;

        if($slug = explode('/', $slug))
            $advertise = $this->getAdvertiseFromSlug($slug, $this::POSITION_RIGHT);

        if($advertise){
            $used = $advertise->getCpc()* $advertise->getClick() + round($advertise->getViews()/1000)*$advertise->getCpm();
            if($used >= $advertise->getBudget()){
                $advertise = null;
            }else{
                $advertise->setViews($advertise->getViews()+1);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        return array('advertise' => $advertise);
    }

    /**
     * @Route("/right")
     * @Template()
     */
    public function rightSlideBarAction($slug, $listSpecial, $likeBox)
    {
        $specials = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:SpecialGroupArticle')
            ->findAllSortByPositionSql();

        $viewBest = $this->getDoctrine()
            ->getRepository('CMSAdminBundle:Article')
            ->findViewBestSql();

        return array(
            'listSpecial' => $listSpecial ? $listSpecial : array(),
            'slug' => isset($slug) && $slug ? $slug : false,
            'likeBox' => $likeBox && $likeBox ? $likeBox : false,
            'specials' => $specials->getResult(),
            'viewBests' => $viewBest->getResult(),
            'em' => $this->getDoctrine()->getRepository('CMSAdminBundle:Article')
        );
    }
    /**
     * @Route("/session")
     * @Template()
     */
    public function sessionAction()
    {
        $session = $this->get('session');

        if (!$session->has('settingWebsite')) {
            $session->set('settingWebsite', $this->getDoctrine()->getRepository('CMSAdminBundle:Setting')->findViewBestSql()->getOneOrNullResult());
        }

        return array();
    }
}
