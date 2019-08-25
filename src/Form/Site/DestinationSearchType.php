<?php

namespace App\Form\Site;

use App\Entity\City;
use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DestinationSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'label' => 'Tên',
                 'attr' => [
                     'placeholder' => 'Nhập từ khoá'
                 ]

            ])
            ->add('city', EntityType::class, [
            'class' => City::class,
            'choice_label' => 'name',
            'placeholder' => 'Điểm đến ...',
            'widget' => 'select2',
            'required' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
