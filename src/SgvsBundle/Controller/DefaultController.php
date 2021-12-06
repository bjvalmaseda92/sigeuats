<?php

namespace SgvsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();

        $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE  c.lugaringreso IS NOT null';
        $query = $em->createQuery($dql);
        $casosingresados = $query->getResult();
        $casos=$em->getRepository('SgvsBundle:Caso')->findAll();

        $pacientes=count($em->getRepository('SgvsBundle:Paciente')->findAll());
        $ca=count($em->getRepository('SgvsBundle:Caso')->findAll());
        $examenes=count($em->getRepository('SgvsBundle:Examen')->findAll());
        $enfermedades=count($em->getRepository('SgvsBundle:Enfermedad')->findAll());


        if (count($casos)!=0) {
             $poringresados=count($casosingresados)/count($casos)*100;
        }else{
        $poringresados=0;
       }

        $fecha=new \DateTime(date('d-m-Y'));
        for ($i = 0; $i < 7; $i++) {

            $dql = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha';
            $dql2 = 'SELECT c FROM SgvsBundle:Caso c WHERE c.fecha = :fecha AND c.lugaringreso IS NOT null';

            $query = $em->createQuery($dql);
            $query2 = $em->createQuery($dql2);
            $query->setParameter('fecha', $fecha);
            $query2->setParameter('fecha', $fecha);


            $detectados[] = count($query->getResult());
            $ingresados[] = count($query2->getResult());
            $ejex[] = $fecha->format('d-m');

            $fecha = mktime(0, 0, 0, $fecha->format('m'), $fecha->format('d') - 1, $fecha->format('Y'));
            $fecha = new \DateTime(date('d-m-Y', $fecha));
        }

        $ingresados = array_reverse($ingresados);
        $detectados = array_reverse($detectados);
        $ejex = array_reverse($ejex);


        return $this->render('SgvsBundle:Default:index.html.twig', array(
            'poringresados'=>$poringresados,
            'ingresados'=>$ingresados,
            'detectados'=>$detectados,
            'ejex'=>$ejex,
            'pacientes'=>$pacientes,
            'casos'=>$ca,
            'examenes'=>$examenes,
            'enfermedades'=>$enfermedades
        ));
    }

    public function loginAction(Request $request)
    {
        $sesion = $request->getSession();
        $error = $request->attributes->get(
            SecurityContextInterface::AUTHENTICATION_ERROR,
            $sesion->get(SecurityContextInterface::AUTHENTICATION_ERROR)
        );
        return $this->render('@Sgvs/Security/login.html.twig', array(
            'last_username'=>$sesion->get(SecurityContextInterface::LAST_USERNAME),
            'error'=>$error));
    }

    public function ayudaAction(){

        return $this->render('SgvsBundle:Default:ayuda.html.twig');
    }

}
