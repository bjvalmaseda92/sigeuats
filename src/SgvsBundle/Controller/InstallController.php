<?php

namespace SgvsBundle\Controller;

use SgvsBundle\Entity\Usuario;
use SgvsBundle\Form\UsuarioInstallType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class InstallController extends Controller
{

    public function userAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $usuarios=$em->getRepository('SgvsBundle:Paciente')->findAll();

        if($usuarios==null){
            $usuario=new Usuario();
            $form=$this->createForm(new UsuarioInstallType(), $usuario);
            $form->handleRequest($request);

            if($form->isSubmitted()&&$form->isValid()){
                $enconder=$this->get('security.encoder_factory')->getEncoder($usuario);
                $usuario->setSalt(md5(time()));
                $passwordCodificado=$enconder->encodePassword(
                    $usuario->getPassword(),
                    $usuario->getSalt()
                );
                $usuario->setPassword($passwordCodificado);
                $usuario->setRol('ROLE_ADMIN');
                $em->persist($usuario);
                $em->flush();

                return $this->redirectToRoute('install_step2');
            }
            return $this->render('SgvsBundle:Install:step-1.html.twig',array('form'=>$form->createView()));
        }else{
            return $this->redirectToRoute('usuario_login');

        }
    }

    public function indexAction(){
        $em=$this->getDoctrine()->getManager();
        $usuarios=$em->getRepository('SgvsBundle:Paciente')->findAll();

        if($usuarios==null){
            return $this->redirectToRoute('install_step1');

        }else{
            return $this->redirectToRoute('usuario_login');

        }


    }

    public function step2Action(){


        return $this->render('SgvsBundle:Install:step-2.html.twig');
    }

}
