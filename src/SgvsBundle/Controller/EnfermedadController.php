<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\Enfermedad;
use SgvsBundle\Form\EnfermedadType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enfermedad controller.
 *
 */
class EnfermedadController extends Controller
{

    /**
     * Lists all Enfermedad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:Enfermedad')->findAll();

        return $this->render('SgvsBundle:Enfermedad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Enfermedad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Enfermedad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Enfermedad añadida con éxito'
            );
            return $this->redirect($this->generateUrl('enfermedad'));

        }

        return $this->render('SgvsBundle:Enfermedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Enfermedad entity.
     *
     * @param Enfermedad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Enfermedad $entity)
    {
        $form = $this->createForm(new EnfermedadType(), $entity, array(
            'action' => $this->generateUrl('enfermedad_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Enfermedad entity.
     *
     */
    public function newAction()
    {
        $entity = new Enfermedad();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:Enfermedad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Enfermedad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Enfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Enfermedad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:Enfermedad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Enfermedad entity.
    *
    * @param Enfermedad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Enfermedad $entity)
    {
        $form = $this->createForm(new EnfermedadType(), $entity, array(
            'action' => $this->generateUrl('enfermedad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Enfermedad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Enfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Enfermedad entity.');
        }

        $editForm = $this->createForm(new EnfermedadType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );
            return $this->redirect($this->generateUrl('enfermedad'));
        }

        return $this->render('SgvsBundle:Enfermedad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Enfermedad entity.
     *
     */
    public function deleteAction( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SgvsBundle:Enfermedad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Enfermedad entity.');
        }

        $em->remove($entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success',
            'Enfermedad <b>'.$entity.'</b> eliminada con éxito'
        );
        return $this->redirect($this->generateUrl('enfermedad'));
    }

    /**
     * Creates a form to delete a Enfermedad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('enfermedad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $enfermedades=$em->getRepository('SgvsBundle:Enfermedad')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:enfermedad.html.twig', array(
            'enfermedades' =>$enfermedades,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Enfermedades existentes SIGEUATS'.date('d-m-Y').'.pdf"'
            )
        );
    }
}
