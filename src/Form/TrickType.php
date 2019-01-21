<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('trickGroup')
            ->add('trickImages', CollectionType::class, array(
                'entry_type' => TrickImageType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => array(
                    'required' => false
                ),
                'by_reference' => false
            ))
            /*->add('trickVideos', CollectionType::class, array(
                'entry_type' => TrickVideoType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => array(
                    'required' => false
                ),
                'by_reference' => false
            ))*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
