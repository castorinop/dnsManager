<?php

namespace castorinop\dnsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecordViewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destination')
            ->add('recordtype')
            //->add('mx')
            ->add('ttl')
            ->add('enable', null, array('required' => false))
            //->add('records')
            ->add('view')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'castorinop\dnsManagerBundle\Entity\RecordView'
        ));
    }

    public function getName()
    {
        return 'castorinop_dnsmanagerbundle_recordviewtype';
    }
}
