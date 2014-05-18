<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\SpecialGroupArticle;
use CMS\AdminBundle\Form\SpecialGroupArticleType;


class SpecialGroupArticleController extends Controller
{

    /**
     * Lists all SpecialGroupArticle entities.
     *
     * @Route("/", name="specgrp")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->findByKeywordSql($keyword);
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
     * Creates a new SpecialGroupArticle entity.
     *
     * @Route("/", name="specgrp_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:SpecialGroupArticle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SpecialGroupArticle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert Special Group were successfull!'
            );

            return $this->redirect($this->generateUrl('specgrp_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a SpecialGroupArticle entity.
    *
    * @param SpecialGroupArticle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(SpecialGroupArticle $entity)
    {
        $form = $this->createForm(new SpecialGroupArticleType(), $entity, array(
            'action' => $this->generateUrl('specgrp_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new SpecialGroupArticle entity.
     *
     * @Route("/new", name="specgrp_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SpecialGroupArticle();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SpecialGroupArticle entity.
     *
     * @Route("/show/{id}", name="specgrp_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SpecialGroupArticle entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing SpecialGroupArticle entity.
     *
     * @Route("edit/{id}", name="specgrp_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SpecialGroupArticle entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SpecialGroupArticle entity.
    *
    * @param SpecialGroupArticle $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SpecialGroupArticle $entity)
    {
        $form = $this->createForm(new SpecialGroupArticleType(), $entity, array(
            'action' => $this->generateUrl('specgrp_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing SpecialGroupArticle entity.
     *
     * @Route("/update/{id}", name="specgrp_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:SpecialGroupArticle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SpecialGroupArticle entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Special Group were successfull!'
            );

            return $this->redirect($this->generateUrl('specgrp_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a SpecialGroupArticle entity.
     *
     * @Route("del/{id}", name="specgrp_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:SpecialGroupArticle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article SpecialGroupArticle.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete Special Group were successfull!'
        );

        return $this->redirect($this->generateUrl('specgrp'));
    }
}
