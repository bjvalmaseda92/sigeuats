<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\LugarIngreso;
use SgvsBundle\Form\LugarIngresoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * LugarIngreso controller.
 *
 */
class LugarIngresoController extends Controller
{

    /**
     * Lists all LugarIngreso entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:LugarIngreso')->findAll();

        return $this->render('SgvsBundle:LugarIngreso:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new LugarIngreso entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new LugarIngreso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Lugar de ingreso añadida con éxito'
            );
            return $this->redirect($this->generateUrl('lugaringreso'));
        }

        return $this->render('SgvsBundle:LugarIngreso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a LugarIngreso entity.
     *
     * @param LugarIngreso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(LugarIngreso $entity)
    {
        $form = $this->createForm(new LugarIngresoType(), $entity, array(
            'action' => $this->generateUrl('lugaringreso_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new LugarIngreso entity.
     *
     */
    public function newAction()
    {
        $entity = new LugarIngreso();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:LugarIngreso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LugarIngreso entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:LugarIngreso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LugarIngreso entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:LugarIngreso:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LugarIngreso entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:LugarIngreso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LugarIngreso entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:LugarIngreso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a LugarIngreso entity.
    *
    * @param LugarIngreso $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(LugarIngreso $entity)
    {
        $form = $this->createForm(new LugarIngresoType(), $entity, array(
            'action' => $this->generateUrl('lugaringreso_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing LugarIngreso entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:LugarIngreso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LugarIngreso entity.');
        }

        $editForm = $this->createForm(new LugarIngresoType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );
            return $this->redirect($this->generateUrl('lugaringreso'));
        }

        return $this->render('SgvsBundle:LugarIngreso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a LugarIngreso entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);


        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SgvsBundle:LugarIngreso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LugarIngreso entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success',
            'Lugar de ingreso eliminado correctamente'
        );
        return $this->redirect($this->generateUrl('lugaringreso'));
    }

    /**
     * Creates a form to delete a LugarIngreso entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lugaringreso_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $lugaresingreso=$em->getRepository('SgvsBundle:LugarIngreso')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:lugaringreso.html.twig', array(
            'lugaresingreso' => $lugaresingreso,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Lugares de ingreso SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
