<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\CmsPage;
use CMS\AdminBundle\Form\CmsPageType;


class CmsPageController extends Controller
{

    /**
     * Lists all CmsPage entities.
     *
     * @Route("/", name="cmspage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:CmsPage')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:CmsPage')->findByKeywordSql($keyword);
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
     * Creates a new CmsPage entity.
     *
     * @Route("/", name="cmspage_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:CmsPage:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new CmsPage();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert CMS Page were successfull!'
            );

            return $this->redirect($this->generateUrl('cmspage_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a CmsPage entity.
    *
    * @param CmsPage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CmsPage $entity)
    {
        $form = $this->createForm(new CmsPageType(), $entity, array(
            'action' => $this->generateUrl('cmspage_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new CmsPage entity.
     *
     * @Route("/new", name="cmspage_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new CmsPage();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a CmsPage entity.
     *
     * @Route("/show/{id}", name="cmspage_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:CmsPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CmsPage entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing CmsPage entity.
     *
     * @Route("/edit/{id}", name="cmspage_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:CmsPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CmsPage entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a CmsPage entity.
    *
    * @param CmsPage $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CmsPage $entity)
    {
        $form = $this->createForm(new CmsPageType(), $entity, array(
            'action' => $this->generateUrl('cmspage_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing CmsPage entity.
     *
     * @Route("/update/{id}", name="cmspage_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:CmsPage:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:CmsPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CmsPage entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update CMS Page were successfull!'
            );

            return $this->redirect($this->generateUrl('cmspage_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a CmsPage entity.
     *
     * @Route("del/{id}", name="cmspage_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:CmsPage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CmsPage entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete CMS Page were successfull!'
        );

        return $this->redirect($this->generateUrl('cmspage'));
    }
}
