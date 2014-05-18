<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\GroupArticle;
use CMS\AdminBundle\Form\GroupArticleType;

class GroupArticleController extends Controller
{
    /**
     * Lists all GroupArticle entities.
     *
     * @Route("/", name="grouparticle")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:GroupArticle')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:GroupArticle')->findByKeywordSql($keyword);
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

        // parameters to template
        return array('pagination' => $pagination);
    }
    /**
     * Creates a new GroupArticle entity.
     *
     * @Route("/create", name="grouparticle_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:GroupArticle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GroupArticle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert Group Article were successfull!'
            );

            return $this->redirect($this->generateUrl('grouparticle_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GroupArticle entity.
     *
     * @param GroupArticle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GroupArticle $entity)
    {
        $form = $this->createForm(new GroupArticleType(), $entity, array(
            'action' => $this->generateUrl('grouparticle_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new GroupArticle entity.
     *
     * @Route("/new", name="grouparticle_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GroupArticle();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GroupArticle entity.
     *
     * @Route("/show/{id}", name="grouparticle_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:GroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupArticle entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing GroupArticle entity.
     *
     * @Route("/edit/{id}", name="grouparticle_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:GroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupArticle entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a GroupArticle entity.
     *
     * @param GroupArticle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GroupArticle $entity)
    {
        $form = $this->createForm(new GroupArticleType(), $entity, array(
            'action' => $this->generateUrl('grouparticle_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing GroupArticle entity.
     *
     * @Route("/update/{id}", name="grouparticle_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:GroupArticle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:GroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupArticle entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Group Article were successfull!'
            );

            return $this->redirect($this->generateUrl('grouparticle_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a GroupArticle entity.
     *
     * @Route("/del/{id}", name="grouparticle_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:GroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupArticle entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete Group Article were successfull!'
        );

        return $this->redirect($this->generateUrl('grouparticle'));
    }
    
}
