<?php

namespace castorinop\dnsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hostname')
            ->add('enable')
            ->add('zone')
//             ->add('domain')
            ->add('views', 'collection', array(
            		'type' => new RecordViewType(),
            		'allow_delete' => true,
            		'allow_add'    => true,
            		'prototype' => true,
            		'prototype_name' => 'view__name__',
            		'by_reference' => false,
            	)
            );
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'castorinop\dnsManagerBundle\Entity\Record'
        ));
    }

    public function getName()
    {
        return 'castorinop_dnsmanagerbundle_recordtype';
    }
}
