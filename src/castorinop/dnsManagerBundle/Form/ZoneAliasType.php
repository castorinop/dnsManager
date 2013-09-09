<?php

namespace castorinop\dnsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ZoneAliasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain')
            ->add('alias')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'castorinop\dnsManagerBundle\Entity\Zone'
        ));
    }

    public function getName()
    {
        return 'castorinop_dnsmanagerbundle_zonealiastype';
    }
}
