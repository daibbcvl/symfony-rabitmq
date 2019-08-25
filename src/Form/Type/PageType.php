<?php

namespace App\Form\Type;

use App\Entity\Country;
use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug-title'],
                'label' => 'Title',
            ])
            ->add('content', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                    'entities_latin' => false,
                ], ])
            ->add('meta', TextType::class, [])
            ->add('keyword', TextType::class, [
            ])
            ->add('titleSeo', TextType::class, [
            ])
            ->add('slug', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug'],
            ])
            ->add('publish', CheckboxType::class, [
                'label' => 'Publish',
                'required' => false,
            ])

        ;
    }
}
