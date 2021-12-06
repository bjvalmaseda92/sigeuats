<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\TipoCaso;
use SgvsBundle\Form\TipoCasoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoCaso controller.
 *
 */
class TipoCasoController extends Controller
{

    /**
     * Lists all TipoCaso entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:TipoCaso')->findAll();

        return $this->render('SgvsBundle:TipoCaso:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new TipoCaso entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new TipoCaso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Tipo de caso añadido correctamente');
            return $this->redirect($this->generateUrl('tipos-casos'));

        }

        return $this->render('SgvsBundle:TipoCaso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a TipoCaso entity.
     *
     * @param TipoCaso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoCaso $entity)
    {
        $form = $this->createForm(new TipoCasoType(), $entity, array(
            'action' => $this->generateUrl('tipos-casos_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoCaso entity.
     *
     */
    public function newAction()
    {
        $entity = new TipoCaso();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:TipoCaso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TipoCaso entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoCaso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCaso entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:TipoCaso:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TipoCaso entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoCaso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCaso entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:TipoCaso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a TipoCaso entity.
    *
    * @param TipoCaso $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoCaso $entity)
    {
        $form = $this->createForm(new TipoCasoType(), $entity, array(
            'action' => $this->generateUrl('tipos-casos_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoCaso entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:TipoCaso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoCaso entity.');
        }

        $editForm = $this->createForm(new TipoCasoType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Los cambios se han guardado corectamente');

            return $this->redirect($this->generateUrl('tipos-casos'));
        }

        return $this->render('SgvsBundle:TipoCaso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a TipoCaso entity.
     *
     */
    public function deleteAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SgvsBundle:TipoCaso')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoCaso entity.');
            }

            $em->remove($entity);
            $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Tipo de caso eliminado correctamente');


        return $this->redirect($this->generateUrl('tipos-casos'));
    }

    /**
     * Creates a form to delete a TipoCaso entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipos-casos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $tiposcasos=$em->getRepository('SgvsBundle:TipoCaso')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:tipocaso.html.twig', array(
            'tiposcasos' => $tiposcasos,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Tipos de casos SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
