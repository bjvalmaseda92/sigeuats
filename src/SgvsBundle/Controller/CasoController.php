<?php

namespace SgvsBundle\Controller;

use SgvsBundle\Util\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\Caso;
use SgvsBundle\Form\CasoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Caso controller.
 *
 */
class CasoController extends Controller
{

    /**
     * Lists all Caso entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:Caso')->findAll();

        return $this->render('SgvsBundle:Caso:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Caso entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Caso();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data=explode(' - ',$entity->getNombrepaciente());
            $paciente=$em->getRepository('SgvsBundle:Paciente')->findOneBy(array('ci'=>$data[1]));
            $enfermedad=$em->getRepository('SgvsBundle:Enfermedad')->findOneBy(array('nombre'=>$entity->getNombreenfermedad()));


            $entity->setCodigo(Util::generatorCode($entity->getFecha(),$paciente->getCi(),$entity->getTipoCaso()));
            $entity->setPaciente($paciente);
            $entity->setEnfermedad($enfermedad);
            $entity->setFecha(new \DateTime($entity->getFecha()));


            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Caso registrado correctamente');
            return $this->redirect($this->generateUrl('casos'));
        }

        $em = $this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();
        $enfermedades=$em->getRepository('SgvsBundle:Enfermedad')->findAll();
        return $this->render('SgvsBundle:Caso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pacientes'=>$pacientes,
            'enfermedades'=>$enfermedades
        ));
    }

    /**
     * Creates a form to create a Caso entity.
     *
     * @param Caso $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Caso $entity)
    {
        $form = $this->createForm(new CasoType(), $entity, array(
            'action' => $this->generateUrl('casos_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Caso entity.
     *
     */
    public function newAction()
    {
        $entity = new Caso();
        $form   = $this->createCreateForm($entity);
        $em=$this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();
        $enfermedades=$em->getRepository('SgvsBundle:Enfermedad')->findAll();

        return $this->render('SgvsBundle:Caso:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pacientes'=>$pacientes,
            'enfermedades'=>$enfermedades
        ));
    }

    /**
     * Finds and displays a Caso entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Caso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caso entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:Caso:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Caso entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Caso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caso entity.');
        }

        $entity->setFecha($entity->getFecha()->format('d-m-Y'));
        $editForm = $this->createEditForm($entity);
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();
        $enfermedades=$em->getRepository('SgvsBundle:Enfermedad')->findAll();
        return $this->render('SgvsBundle:Caso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pacientes'   =>$pacientes,
            'enfermedades' =>$enfermedades
        ));
    }

    /**
    * Creates a form to edit a Caso entity.
    *
    * @param Caso $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Caso $entity)
    {
        $form = $this->createForm(new CasoType(), $entity, array(
            'action' => $this->generateUrl('casos_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Caso entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Caso')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Caso entity.');
        }

        $entity->setFecha($entity->getFecha()->format('d-m-Y'));
        $editForm = $this->createForm(new CasoType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setFecha(new \DateTime($entity->getFecha()));

            $data=explode(' - ',$entity->getNombrepaciente());
            $paciente=$em->getRepository('SgvsBundle:Paciente')->findOneBy(array('ci'=>$data[1]));
            $enfermedad=$em->getRepository('SgvsBundle:Enfermedad')->findOneBy(array('nombre'=>$entity->getNombreenfermedad()));

            $entity->setPaciente($paciente);
            $entity->setEnfermedad($enfermedad);

            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Los cambios se han guardado con éxito');


            return $this->redirect($this->generateUrl('casos'));
        }
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();
        $enfermedades=$em->getRepository('SgvsBundle:Enfermedad')->findAll();
        return $this->render('SgvsBundle:Caso:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pacientes'   =>$pacientes,
            'enfermedades' =>$enfermedades
        ));
    }
    /**
     * Deletes a Caso entity.
     *
     */
    public function deleteAction($id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SgvsBundle:Caso')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Caso entity.');
            }

            $em->remove($entity);
            $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Caso '.$entity->getCodigo().' eliminado correctamente');
        return $this->redirect($this->generateUrl('casos'));
    }

    /**
     * Creates a form to delete a Caso entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('casos_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $casos=$em->getRepository('SgvsBundle:Caso')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:casos.html.twig', array(
            'casos' => $casos,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Casos SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
