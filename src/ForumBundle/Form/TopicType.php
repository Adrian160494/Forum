<?php

namespace ForumBundle\Form;

use ForumBundle\ForumBundle;
use Symfony\Component\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class TopicType extends AbstractType{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'topic';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('topic','text',array('label'=>'Nowy temat','attr'=>array('class'=>'form-control','placeholder'=>'Dodaj temat')))
            ->add('Dodaj','submit',array('attr'=>array('class'=>'btn btn-primary')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class'=>'ForumBundle\Entity\Topic',]);
    }
}