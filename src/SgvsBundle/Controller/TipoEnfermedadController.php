<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\TipoEnfermedad;
use SgvsBundle\Form\TipoEnfermedadType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoEnfermedad controller.
 *
 */
class TipoEnfermedadController extends Controller
{

    /**
     * Lists all TipoEnfermedad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:TipoEnfermedad')->findAll();

        return $this->render('SgvsBundle:TipoEnfermedad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TipoEnfermedad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TipoEnfermedad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Tipo de enfermedad añadido con éxito'
            );
            return $this->redirect($this->generateUrl('tipoenfermedad'));
        }

        return $this->render('SgvsBundle:TipoEnfermedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TipoEnfermedad entity.
     *
     * @param TipoEnfermedad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoEnfermedad $entity)
    {
        $form = $this->createForm(new TipoEnfermedadType(), $entity, array(
            'action' => $this->generateUrl('tipoenfermedad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoEnfermedad entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoEnfermedad();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:TipoEnfermedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TipoEnfermedad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoEnfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEnfermedad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:TipoEnfermedad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TipoEnfermedad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoEnfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEnfermedad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:TipoEnfermedad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TipoEnfermedad entity.
    *
    * @param TipoEnfermedad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoEnfermedad $entity)
    {
        $form = $this->createForm(new TipoEnfermedadType(), $entity, array(
            'action' => $this->generateUrl('tipoenfermedad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoEnfermedad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoEnfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoEnfermedad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TipoEnfermedadType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );
            return $this->redirect($this->generateUrl('tipoenfermedad'));
        }

        return $this->render('SgvsBundle:TipoEnfermedad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a TipoEnfermedad entity.
     *
     */
    public function deleteAction( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SgvsBundle:TipoEnfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AreaDeSalud entity.');
        }



        $em->remove($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success',
            'Tipo de enfermedad '.$entity.' eliminada con éxito'
        );
        return $this->redirect($this->generateUrl('tipoenfermedad'));
    }


    /**
     * Creates a form to delete a TipoEnfermedad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoenfermedad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $tiposenfermedades=$em->getRepository('SgvsBundle:TipoEnfermedad')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:tipoenfermedad.html.twig', array(
            'tiposenfermedades' => $tiposenfermedades,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Tipos de enfermedades SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
