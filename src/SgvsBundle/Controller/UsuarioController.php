<?php

namespace SgvsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SgvsBundle\Entity\Usuario;
use SgvsBundle\Form\UsuarioType;
use SgvsBundle\Form\UsuarioEditType;


/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{

    /**
     * Lists all Usuario entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SgvsBundle:Usuario')->findAll();

        return $this->render('SgvsBundle:Usuario:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Usuario entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $enconder=$this->get('security.encoder_factory')->getEncoder($entity);
            $entity->setSalt(md5(time()));
            $passwordCodificado=$enconder->encodePassword(
                $entity->getPassword(),
                $entity->getSalt()
            );
            $entity->setPassword($passwordCodificado);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_show', array('id' => $entity->getId())));
        }

        $this->get('session')->getFlashBag()->add('success',
            'Usuario adicionado correctamente'
        );

        return $this->render('SgvsBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity);

        return $this->render('SgvsBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Usuario entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        return $this->render('SgvsBundle:Usuario:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioEditType(), $entity);

        return $this->render('SgvsBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


    /**
     * Edits an existing Usuario entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SgvsBundle:Usuario')->find($id);
        $passwordOriginal = $entity->getPassword();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioEditType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if (null == $entity->getPassword()) {
                $entity->setPassword($passwordOriginal);
            } else {
                $encoder = $this->get('security.encoder_factory')
                    ->getEncoder($entity);
                $passwordCodificado = $encoder->encodePassword(
                    $entity->getPassword(),
                    $entity->getSalt()
                );
                $entity->setPassword($passwordCodificado);
            }

            $em->flush();
            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );

            return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
        }

        return $this->render('SgvsBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Usuario entity.
     *
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SgvsBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();

        $this->get('session')->getFlashBag()->add('success',
            'Usuario eliminado correctamente'
        );

        return $this->redirect($this->generateUrl('usuario'));
    }


    public function profileAction()
    {
        $entity = $this->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioEditType(), $entity);

        return $this->render('SgvsBundle:Usuario:profile.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }



    public function profileUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('security.context')->getToken()->getUser();
        $passwordOriginal = $entity->getPassword();
        $roleoriginal=$entity->getRol();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioEditType(), $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if (null == $entity->getPassword()) {
                $entity->setPassword($passwordOriginal);
            } else {
                $encoder = $this->get('security.encoder_factory')
                    ->getEncoder($entity);
                $passwordCodificado = $encoder->encodePassword(
                    $entity->getPassword(),
                    $entity->getSalt()
                );
                $entity->setPassword($passwordCodificado);
            }
            $entity->setRol($roleoriginal);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success',
                'Los cambios se han guardado con éxito'
            );

            return $this->redirect($this->generateUrl('usuario_profile'));
        }

        return $this->render('@Sgvs/Usuario/profile.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }


}
