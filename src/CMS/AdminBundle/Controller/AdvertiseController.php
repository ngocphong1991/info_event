<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\Advertise;
use CMS\AdminBundle\Form\AdvertiseType;
use CMS\AdminBundle\Api\GetRoleApi;

class AdvertiseController extends Controller
{
    public $roles;

    public function __construct(){
        $this->roles = new GetRoleApi();
    }

    /**
     * Lists all Advertise entities.
     *
     * @Route("/", name="advertise")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:Advertise')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:Advertise')->findByKeywordSql($keyword);
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
     * Creates a new Advertise entity.
     *
     * @Route("/create", name="advertise_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:Advertise:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $entity = new Advertise();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert Advertise were successfull!'
            );
            return $this->redirect($this->generateUrl('advertise_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Advertise entity.
    *
    * @param Advertise $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Advertise $entity)
    {
        $form = $this->createForm(new AdvertiseType(), $entity, array(
            'action' => $this->generateUrl('advertise_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Advertise entity.
     *
     * @Route("/new", name="advertise_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $entity = new Advertise();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Advertise entity.
     *
     * @Route("/show/{id}", name="advertise_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Advertise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertise entity.');
        }
        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Advertise entity.
     *
     * @Route("/edit/{id}", name="advertise_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Advertise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertise entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Advertise entity.
    *
    * @param Advertise $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Advertise $entity)
    {
        $form = $this->createForm(new AdvertiseType(), $entity, array(
            'action' => $this->generateUrl('advertise_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Advertise entity.
     *
     * @Route("/update/{id}", name="advertise_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:Advertise:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Advertise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertise entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Advertise were successfull!'
            );
            
            return $this->redirect($this->generateUrl('advertise_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Advertise entity.
     *
     * @Route("/del/{id}", name="advertise_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {   
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['del'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:Advertise')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Advertise entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Delete Advertise were successfull!'
        );

        return $this->redirect($this->generateUrl('advertise'));
    }
}
