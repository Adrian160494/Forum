<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegisterType extends AbstractType{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'register';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text',array('label'=>'Username','attr'=>array('class'=>'form-control')))
            ->add('password','password',array('label'=>'Password','attr'=>array('class'=>'form-control')))
            ->add('email','text',array('label'=>'Email','attr'=>array('class'=>'form-control')))
        ->add('Add','submit',array('attr'=>array('class'=>'btn btn-success')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>'ForumBundle\Entity\User',
        ]);
    }
}