<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\Paciente;
use SgvsBundle\Form\PacienteType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Paciente controller.
 *
 */
class PacienteController extends Controller
{

    /**
     * Lists all Paciente entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:Paciente')->findAll();

        return $this->render('SgvsBundle:Paciente:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Paciente entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Paciente();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Paciente añadido con éxito'
            );
            return $this->redirect($this->generateUrl('pacientes'));        }

        return $this->render('SgvsBundle:Paciente:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Paciente entity.
     *
     * @param Paciente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Paciente $entity)
    {
        $form = $this->createForm(new PacienteType(), $entity, array(
            'action' => $this->generateUrl('pacientes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Paciente entity.
     *
     */
    public function newAction()
    {
        $entity = new Paciente();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:Paciente:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Paciente entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:Paciente:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Paciente entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:Paciente:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Paciente entity.
     *
     * @param Paciente $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Paciente $entity)
    {
        $form = $this->createForm(new PacienteType(), $entity, array(
            'action' => $this->generateUrl('pacientes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Paciente entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }

        $editForm = $this->createForm(new PacienteType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );
            return $this->redirect($this->generateUrl('pacientes'));
        }

        return $this->render('SgvsBundle:Paciente:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Paciente entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SgvsBundle:Paciente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paciente entity.');
        }



        $em->remove($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success',
            'Paciente '.$entity.' eliminada con éxito'
        );

        return $this->redirect($this->generateUrl('pacientes'));
    }

    /**
     * Creates a form to delete a Paciente entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pacientes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:paciente.html.twig', array(
            'pacientes' => $pacientes,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Pacientes SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
