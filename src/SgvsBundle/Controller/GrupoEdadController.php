<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\GrupoEdad;
use SgvsBundle\Form\GrupoEdadType;
use Symfony\Component\HttpFoundation\Response;

/**
 * GrupoEdad controller.
 *
 */
class GrupoEdadController extends Controller
{

    /**
     * Lists all GrupoEdad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:GrupoEdad')->findAll();

        return $this->render('SgvsBundle:GrupoEdad:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new GrupoEdad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new GrupoEdad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Grupo de edad añadido con éxito'
            );
            return $this->redirect($this->generateUrl('grupo-edades'));

        }

        return $this->render('SgvsBundle:GrupoEdad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a GrupoEdad entity.
     *
     * @param GrupoEdad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrupoEdad $entity)
    {
        $form = $this->createForm(new GrupoEdadType(), $entity, array(
            'action' => $this->generateUrl('grupo-edades_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new GrupoEdad entity.
     *
     */
    public function newAction()
    {
        $entity = new GrupoEdad();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:GrupoEdad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a GrupoEdad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:GrupoEdad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrupoEdad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:GrupoEdad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing GrupoEdad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:GrupoEdad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrupoEdad entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:GrupoEdad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a GrupoEdad entity.
     *
     * @param GrupoEdad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrupoEdad $entity)
    {
        $form = $this->createForm(new GrupoEdadType(), $entity, array(
            'action' => $this->generateUrl('grupo-edades_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing GrupoEdad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:GrupoEdad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrupoEdad entity.');
        }

        $editForm = $this->createForm(new GrupoEdadType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios han sido guardados con éxito'
            );
            return $this->redirect($this->generateUrl('grupo-edades'));        }

        return $this->render('SgvsBundle:GrupoEdad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a GrupoEdad entity.
     *
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SgvsBundle:GrupoEdad')->find($id);

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success',
            'Grupo de edad eliminado correctamente'
        );
        return $this->redirect($this->generateUrl('grupo-edades'));
    }

    /**
     * Creates a form to delete a GrupoEdad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo-edades_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $gruposedad=$em->getRepository('SgvsBundle:GrupoEdad')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:grupoedad.html.twig', array(
            'gruposedad' => $gruposedad,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Grupo de edades SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
