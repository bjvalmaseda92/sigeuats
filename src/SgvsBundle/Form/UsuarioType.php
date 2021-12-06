<?php

namespace SgvsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('nombreUsuario')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Confirmar contraseña'),
            ))
            ->add('correo')
            ->add('rol', 'choice', array(
                'choices' => array('ROLE_USUARIO' => 'Usuario','ROLE_CENTRO'=>'Centro de Salud','ROLE_TECNICO'=>'Técnico UATS', 'ROLE_ADMIN' => 'Administrador',)
            ))
            ->add('picture', new ImagenType(), array('required'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SgvsBundle\Entity\Usuario', 'cascade_validation'=>'true'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sgvsbundle_usuario';
    }
}
