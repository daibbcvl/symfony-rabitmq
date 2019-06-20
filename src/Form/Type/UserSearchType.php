<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSearchType extends AbstractSearchType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'required' => false,
            ])
            ->add('enabled', ChoiceType::class, [
                'required' => false,
                'label' => 'Status',
                'placeholder' => 'All',
                'choices' => [
                    'Enabled' => 1,
                    'Disabled' => 0,
                ],
            ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
