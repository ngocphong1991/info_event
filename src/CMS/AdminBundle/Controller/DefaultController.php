<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Yaml\Yaml;

class DefaultController extends Controller
{
    /**
     * Website root. Automatically redirect to preferred culture based on browser settings
     *
     * @Route("/", name="root")
     */
    public function rootAction()
    {
        $weblangs = $this->container->getParameter('langs');
        $lang = $this->getRequest()->getPreferredLanguage($weblangs);

        return $this->redirect($this->generateUrl('article'));
        /*
        return $this->redirect(
                $this->generateUrl('index', array('_locale' => $lang))
                );*/
    }
 
    /**
     * Website Index. Culture must be set and be either es (Spanish), en (English) or ca (Catalan)
     *
     * @Route("/{_locale}", requirements={"_locale"="es|en|ca"}, name="index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/menu")
     * @Template()
     */
    public function menuAction()
    {
        $file   = __DIR__."/../Resources/config/resources.yml";
        $resources = Yaml::parse(file_get_contents($file));
        $acl = $resources['acl'];

        $em = $this->getDoctrine()->getManager();
        $groupArticle = $em->getRepository('CMSAdminBundle:GroupArticle')->findAllSql()->getResult();

        return array('acl' => $acl, 'groupArticle' => $groupArticle);
    }

    /**
     * @Route("/no-item")
     * @Template()
     */
    public function noItemAction()
    {
        return array();
    }
}
