<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'post';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message','textarea',array('label'=>'Post','attr'=>array('style'=>'height: 130px','class'=>'form-control','length'=>255,'placeholder'=>'Dodaj nowy post')))
            ->add('Dodaj','submit',array('attr'=>array('class'=>'btn btn-primary')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'ForumBundle\Entity\Message',
        ]);
    }
}