<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
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
                'label' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '__images_prot__'
            ))
            ->add('trickVideos', CollectionType::class, array(
                'entry_type' => TrickVideoType::class,
                'label' => false,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '__videos_prot__'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'allow_extra_fields' => true
        ]);
    }
}
