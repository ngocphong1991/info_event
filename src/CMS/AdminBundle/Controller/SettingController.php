<?php

namespace CMS\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\AdminBundle\Entity\Setting;
use CMS\AdminBundle\Form\SettingType;
use CMS\AdminBundle\Api\GetRoleApi;

class SettingController extends Controller
{
    public $roles;

    public function __construct(){
        $this->roles = new GetRoleApi();
    }

    /**
     * Lists all Setting entities.
     *
     * @Route("/", name="setting")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'setting'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Setting')->findViewBestSql()->getOneOrNullResult();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
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
     * @param Setting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Setting $entity)
    {
        $form = $this->createForm(new SettingType(), $entity, array(
            'action' => $this->generateUrl('setting_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Setting entity.
     *
     * @Route("/update/{id}", name="setting_update")
     * @Method("PUT")
     * @Template("CMSAdminBundle:Advertise:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        if(!$this->roles->checkACL($this->getUser()->getRoles(), $this->roles->acl['edit'], 'advertise'))
            throw $this->createNotFoundException('You can not execute this function, please contact administrator!');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CMSAdminBundle:Setting')->findViewBestSql()->getOneOrNullResult();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Setting entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'successfull',
                'Update Advertise were successfull!'
            );

            return $this->redirect($this->generateUrl('setting'));
        }else{
            $this->get('session')->getFlashBag()->add(
                'error',
                'Error! Please review your data input'
            );

            return $this->redirect($this->generateUrl('setting'));
        }
    }
}