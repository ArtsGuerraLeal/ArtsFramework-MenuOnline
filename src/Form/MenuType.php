<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telephone')
            ->add('whatsapp')
            ->add('greeting')
            ->add('name')
            ->add('notes')
            ->add('title')
            ->add('description')
            ->add('info')
            ->add('website')
            ->add('attachment',FileType::class , [
                'mapped' => false,
                'required' => true
            ])
            ->add('save',SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
