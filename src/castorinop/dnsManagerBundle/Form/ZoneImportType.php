<?php

namespace castorinop\dnsManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ZoneImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain')
            ->add('server', 'text', array('required' => false))
            ->add('keyname', 'text', array('required' => false))
            ->add('key', 'text', array('required' => false))
            ->add('view', 'entity', array(
    						'class' => 'dnsManagerBundle:View',
    						'query_builder' => function($repository) { return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC'); },
    						'property' => 'name',
    						)
            )
            ->add('flush', 'checkbox', array('required'  => false))
        		;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setOptional(array('server'));
    }

    public function getName()
    {
        return 'castorinop_dnsmanagerbundle_zoneimporttype';
    }
}
