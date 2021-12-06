<?php

namespace SgvsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CasoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', 'text')
            ->add('nombrepaciente')
            ->add('nombreenfermedad')
            ->add('tipoCaso')
            ->add('info')
            ->add('lugaringreso')
            ->add('areadesalud')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SgvsBundle\Entity\Caso'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sgvsbundle_caso';
    }
}
