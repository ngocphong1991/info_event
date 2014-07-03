<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\Article;
use CMS\AdminBundle\Form\ArticleType;
use CMS\AdminBundle\Api\GetRoleApi;

class ArticleController extends Controller
{
    public $roles;

    public function __construct(){
        $this->roles = new GetRoleApi();
    }

    /**
     * Lists all Article entities.
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $keyword = $this->get('request')->query->get('keyword', '');
        $em    = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:Article')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:Article')->findByKeywordSql($keyword);
            $count = count($query->getResult());

            if($count)  $this->get('session')->getFlashBag()->add(
                'successfull',
                "Have $count result with keyword \"<em><b> $keyword </b></em>\""
            );
            else    $this->get('session')->getFlashBag()->add(
                'error',
                "No reult with keyword \"<em><b> $keyword </b></em>\""
            );
        }

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        //Define status
        $status = array(
            0 => 'Cancel',
            1 => 'Post',
            2 => 'Save draft',
            3 => 'Pending Approve',
            4 => 'Pending Post'
        );

        // parameters to template
        return array('pagination' => $pagination, 'status' => $status);
    }

    /**
     * Lists all Article entities.
     *
     * @Route("/group/{idGroup}", name="article_group")
     * @Method("GET")
     * @Template()
     */
    public function groupAction($idGroup)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        if(!$idGroup || !is_numeric($idGroup))
            throw $this->createNotFoundException('Have not parameter idGroup!');

        $keyword = $this->get('request')->query->get('keyword', '');
        $em    = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:Article')->findAllByGroupSql($idGroup);
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:Article')->findByKeywordSql($keyword);
            $count = count($query->getResult());

            if($count)  $this->get('session')->getFlashBag()->add(
                'successfull',
                "Have $count result with keyword \"<em><b> $keyword </b></em>\""
            );
            else    $this->get('session')->getFlashBag()->add(
                'error',
                "No reult with keyword \"<em><b> $keyword </b></em>\""
            );
        }

        //Pager
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        //Define status
        $status = array(
            0 => 'Cancel',
            1 => 'Post',
            2 => 'Save draft',
            3 => 'Pending Approve',
            4 => 'Pending Post'
        );

        // parameters to template
        return array('pagination' => $pagination, 'status' => $status, 'idGroup' => $idGroup);
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/create", name="article_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:Article:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $isLocked = $this->roles->isAdministrator($this->getUser()->getRoles());

        $entity = new Article();
        $form = $this->createCreateForm($entity, $isLocked);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert Article were successfull!'
            );

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
    * Creates a form to create a Article entity.
    *
    * @param Article $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Article $entity, $isLocked = false, $em = null, $idGroup = 0)
    {
        $form = $this->createForm(new ArticleType($isLocked, $em, $idGroup), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     * @Route("/new", name="article_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();
        $isLocked = $this->roles->isAdministrator($this->getUser()->getRoles());
        $idGroup = $keyword = $this->get('request')->query->get('group', '');

        $entity = new Article();
        if($idGroup && $em->getRepository('CMSAdminBundle:GroupArticle')->find($idGroup))
            $form   = $this->createCreateForm($entity, $isLocked, $this->getDoctrine()->getManager(), $idGroup);
        else
            $form   = $this->createCreateForm($entity, $isLocked);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/show/{id}", name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/edit/{id}", name="article_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $isLocked = $this->roles->isAdministrator($this->getUser()->getRoles());


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        if($entity->getIsLocked() && !$isLocked){
            $this->get('session')->getFlashBag()->add(
                'error',
                'This article have been locked. If you want to edit it, Please contact with Administrator!'
            );
            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        $editForm = $this->createEditForm($entity, $isLocked);

        return array(
            'entity'      => $entity,
            'form'        => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Article entity.
    *
    * @param Article $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Article $entity, $isLocked = false)
    {
        $form = $this->createForm(new ArticleType($isLocked), $entity, array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Article entity.
     *
     * @Route("/update/{id}", name="article_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:Article:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $isLocked = $this->roles->isAdministrator($this->getUser()->getRoles());

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        if($entity->getIsLocked() && !$isLocked){
            $this->get('session')->getFlashBag()->add(
                'error',
                'This article have been locked. If you want to edit it, Please contact with Administrator!'
            );
            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        $editForm = $this->createEditForm($entity, $isLocked);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Article were successfull!'
            );

            return $this->redirect($this->generateUrl('article_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Article entity.
     *
     * @Route("/del/{id}", name="article_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'article'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete Article were successfull!'
        );

        return $this->redirect($this->generateUrl('article'));
    }
}
