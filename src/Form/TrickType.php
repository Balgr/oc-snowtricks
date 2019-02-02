<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom du trick'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description'
            ))
            ->add('trickGroup', EntityType::class, array(
                'class' => TrickGroup::class
            ))
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
