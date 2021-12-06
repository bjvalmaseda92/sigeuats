<?php

namespace SgvsBundle\Controller;

use SgvsBundle\Util\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use SgvsBundle\Entity\AreaDeSalud;
use SgvsBundle\Form\AreaDeSaludType;
use Symfony\Component\HttpFoundation\Response;

/**
 * AreaDeSalud controller.
 *
 */
class AreaDeSaludController extends Controller
{

    /**
     * Lists all AreaDeSalud entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:AreaDeSalud')->findAll();

        return $this->render('SgvsBundle:AreaDeSalud:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new AreaDeSalud entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new AreaDeSalud();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $telefono=Util::stringToPhone($entity->getTelefono());
            $entity->setTelefono($telefono);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',
                'Área de salud añadida con éxito'
            );
            return $this->redirect($this->generateUrl('areasalud'));
        }

        return $this->render('SgvsBundle:AreaDeSalud:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a AreaDeSalud entity.
     *
     * @param AreaDeSalud $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AreaDeSalud $entity)
    {
        $form = $this->createForm(new AreaDeSaludType(), $entity, array(
            'action' => $this->generateUrl('areasalud_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AreaDeSalud entity.
     *
     */
    public function newAction()
    {
        $entity = new AreaDeSalud();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:AreaDeSalud:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing AreaDeSalud entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:AreaDeSalud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AreaDeSalud entity.');
        }
        $entity->setTelefono(Util::phoneToString($entity->getTelefono()));
        $editForm = $this->createForm(new AreaDeSaludType(), $entity);

        return $this->render('SgvsBundle:AreaDeSalud:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


    /**
     * Edits an existing AreaDeSalud entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:AreaDeSalud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AreaDeSalud entity.');
        }
        $entity->setTelefono(Util::phoneToString($entity->getTelefono()));
        $editForm = $this->createForm(new AreaDeSaludType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setTelefono(Util::stringToPhone($entity->getTelefono()));
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );
            return $this->redirect($this->generateUrl('areasalud'));
        }

        return $this->render('SgvsBundle:AreaDeSalud:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a AreaDeSalud entity.
     *
     */
    public function deleteAction( $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SgvsBundle:AreaDeSalud')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AreaDeSalud entity.');
            }

            $em->remove($entity);
            $em->flush();
        $this->get('session')->getFlashBag()->add('success',
            'Área de salud '.$entity.' eliminada con éxito'
        );
        return $this->redirect($this->generateUrl('areasalud'));
    }

    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $areas=$em->getRepository('SgvsBundle:AreaDeSalud')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:area.html.twig', array(
            'areas' => $areas,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Áreas de salud SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }


}
