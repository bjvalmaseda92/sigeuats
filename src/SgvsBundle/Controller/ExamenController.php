<?php

namespace SgvsBundle\Controller;

use SgvsBundle\Entity\Paciente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\Examen;
use SgvsBundle\Form\ExamenType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Examen controller.
 *
 */
class ExamenController extends Controller
{

    /**
     * Lists all Examen entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:Examen')->findBy(array(), array('fecha'=>'DESC'));

        return $this->render('SgvsBundle:Examen:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Examen entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Examen();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fecha=new \DateTime($entity->getFecha());

            $data=explode(' - ',$entity->getNombrepaciente());
            $paciente=$em->getRepository('SgvsBundle:Paciente')->findOneBy(array('ci'=>$data[1]));
            $entity->setPaciente($paciente);
            $entity->setFecha($fecha);

            if($entity->getFluorecencia()>34.5 && $entity->getFluorecencia()<65.3){
                $entity->setResultado('Positivo para Dengue');
            }else if($entity->getFluorecencia()>=65.3){
                $entity->setResultado('Positivo para virus Zika');
            }else{
                $entity->setResultado('Negativo');
            }


            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Examen añadido correctamente'
            );
            return $this->redirect($this->generateUrl('examenes'));

        }
        $em=$this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();
        return $this->render('SgvsBundle:Examen:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pacientes'=>$pacientes,

        ));
    }

    /**
     * Creates a form to create a Examen entity.
     *
     * @param Examen $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Examen $entity)
    {
        $form = $this->createForm(new ExamenType(), $entity, array(
            'action' => $this->generateUrl('examenes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Examen entity.
     *
     */
    public function newAction()
    {
        $entity = new Examen();
        $form   = $this->createCreateForm($entity);
        $em=$this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();

        return $this->render('SgvsBundle:Examen:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'pacientes'=>$pacientes,
        ));
    }

    /**
     * Finds and displays a Examen entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Examen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Examen entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SgvsBundle:Examen:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Examen entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();


        $entity = $em->getRepository('SgvsBundle:Examen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Examen entity.');
        }
        $entity->setFecha($entity->getFecha()->format('d-m-Y'));
        $editForm = $this->createForm(new ExamenType(), $entity);

        return $this->render('SgvsBundle:Examen:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pacientes'   =>$pacientes
        ));
    }


    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Examen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Examen entity.');
        }
        $entity->setFecha($entity->getFecha()->format('d-m-Y'));
        $editForm = $this->createForm(new ExamenType(),$entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setFecha(new \DateTime($entity->getFecha()));
            $data=explode(' - ',$entity->getNombrepaciente());
            $paciente=$em->getRepository('SgvsBundle:Paciente')->findOneBy(array('ci'=>$data[1]));
            $entity->setPaciente($paciente);

            if($entity->getFluorecencia()>34.5 && $entity->getFluorecencia()<65.3){
                $entity->setResultado('Positivo para Dengue');
            }else if($entity->getFluorecencia()>=65.3){
                $entity->setResultado('Positivo para virus Zika');
            }else{
                $entity->setResultado('Negativo');
            }


            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                'Los datos se han guardado correctemente'
            );
            return $this->redirect($this->generateUrl('examenes'));
        }


        $pacientes=$em->getRepository('SgvsBundle:Paciente')->findAll();

        return $this->render('SgvsBundle:Examen:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'pacientes'   =>$pacientes
        ));
    }
    /**
     * Deletes a Examen entity.
     *
     */
    public function deleteAction( $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SgvsBundle:Examen')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Examen entity.');
            }

            $em->remove($entity);
            $em->flush();
        $this->get('session')->getFlashBag()->add('success',
            'Examen eliminado con éxito'
        );

        return $this->redirect($this->generateUrl('examenes'));
    }
    public function pdfAction(){
        $em=$this->getDoctrine()->getManager();
        $examenes=$em->getRepository('SgvsBundle:Examen')->findAll();

        $html = $this->renderView('SgvsBundle:Informes:Examen.html.twig', array(
            'examenes' => $examenes,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="Exámenes SIGEUATS '.date('d-m-Y').'.pdf"'
            )
        );
    }
}
