<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\Tag;
use CMS\AdminBundle\Form\TagType;


class TagController extends Controller
{

    /**
     * Lists all Tag entities.
     *
     * @Route("/", name="tag")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:Tag')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:Tag')->findByKeywordSql($keyword);
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
     * Creates a new Tag entity.
     *
     * @Route("/", name="tag_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:Tag:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Tag();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert new tag were successfull!'
            );

            return $this->redirect($this->generateUrl('tag_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Tag entity.
    *
    * @param Tag $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Tag $entity)
    {
        $form = $this->createForm(new TagType(), $entity, array(
            'action' => $this->generateUrl('tag_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     * @Route("/new", name="tag_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tag();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tag entity.
     *
     * @Route("/show/{id}", name="tag_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/edit/{id}", name="tag_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Tag entity.
    *
    * @param Tag $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tag $entity)
    {
        $form = $this->createForm(new TagType(), $entity, array(
            'action' => $this->generateUrl('tag_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Tag entity.
     *
     * @Route("/update/{id}", name="tag_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:Tag:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update this tag were successfull!'
            );

            return $this->redirect($this->generateUrl('tag_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Tag entity.
     *
     * @Route("/del/{id}", name="tag_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete tag were successfull!'
        );

        return $this->redirect($this->generateUrl('tag'));
    }
}
