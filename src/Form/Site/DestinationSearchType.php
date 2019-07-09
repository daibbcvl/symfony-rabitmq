<?php

namespace App\Form\Site;

use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DestinationSearchType extends AbstractType
{
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        parent::configureOptions($resolver);
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('city', EntityType::class, [
            'class' => Post::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')->where('p.type = :type')->setParameter('type', 'destination');
            },
            'choice_label' => 'title',
            'placeholder' => 'Điểm đến ...',
            'widget' => 'select2',
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
