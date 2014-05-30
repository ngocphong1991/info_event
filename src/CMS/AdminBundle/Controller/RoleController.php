<?php

namespace CMS\AdminBundle\Controller;

use JsonSchema\Constraints\Object;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\Role;
use CMS\AdminBundle\Form\RoleType;
use CMS\AdminBundle\Api\GetRoleApi;

class RoleController extends Controller
{
    public $roles;

    public function __construct(){
        $this->roles = new GetRoleApi();
    }

    /**
     * Lists all Role entities.
     *
     * @Route("/", name="role")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $keyword = $this->get('request')->query->get('keyword', '');
        $em = $this->getDoctrine()->getManager();

        if(!$keyword) $query = $em->getRepository('CMSAdminBundle:Role')->findAllSql();
        else{//Searching
            $query = $em->getRepository('CMSAdminBundle:Role')->findByKeywordSql($keyword);
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
     * Creates a new Role entity.
     *
     * @Route("/create", name="role_create")
     * @Method("POST")
     * @Template("CMSAdminBundle:Role:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = new Role();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Insert Role were successfull!'
            );

            return $this->redirect($this->generateUrl('role_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'resources' => $this->roles->resources,
        );
    }

    /**
     * Creates a form to create a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Role $entity)
    {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Role entity.
     *
     * @Route("/new", name="role_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['add'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = new Role();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'resources' => $this->roles->resources
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/show/{id}", name="role_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['view'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit", name="role_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $permission = (array) json_decode($entity->getResource());

        foreach($this->roles->resources as $key => $resource){
            if(array_key_exists($resource['code'], $permission)){
                $this->roles->resources[$key]['acl'] = $permission[$resource['code']];
            }
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'    => $entity,
            'form'      => $editForm->createView(),
            'resources' => $this->roles->resources,
            'acl'       => $this->roles->acl
        );
    }

    /**
     * Creates a form to edit a Role entity.
     *
     * @param Role $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Role $entity)
    {
        $form = $this->createForm(new RoleType(), $entity, array(
            'action' => $this->generateUrl('role_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Role entity.
     *
     * @Route("/update/{id}", name="role_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:Role:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Role were successfull!'
            );

            return $this->redirect($this->generateUrl('role_show', array('id' => $id)));
        }

        return array(
            'entity'    => $entity,
            'form'      => $editForm->createView(),
            'resources' => $this->roles->resources
        );
    }
    /**
     * Deletes a Role entity.
     *
     * @Route("/del/{id}", name="role_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['del'], 'role'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CMSAdminBundle:Role')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Role entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'successfull',
            'Insert Role were successfull!'
        );

        return $this->redirect($this->generateUrl('role'));
    }
}
