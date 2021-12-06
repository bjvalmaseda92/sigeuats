<?php

namespace SgvsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioInstallType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('apellidos', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('nombreUsuario', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Confirmar contraseña' ),
            ))
            ->add('correo', 'email', array('attr'=>array('class'=>'form-control')))
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
