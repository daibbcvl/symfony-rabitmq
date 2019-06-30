<?php

namespace App\Form\Type;


use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
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
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
            ])
            ->add('title', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug-title']
            ])
            ->add('summary', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#22A7F0',
                    'entities_latin' => false
                )))
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                    'entities_latin' => false
                )))
            ->add('meta', TextType::class, [
            ])
            ->add('keyword', TextType::class, [
            ])
            ->add('titleSeo', TextType::class, [
            ])
            ->add('thumbUrl', FileType::class, [
                'data' => null,
                'required' => false
            ])
            ->add('slug', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug']
            ])

            ->add('publishedAt', DateType::class, [
                'constraints' => new NotBlank()
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'class' => Tag::class,
                'multiple' => true,
                'widget' => 'select2',
            ])

            ->add('publish', CheckboxType::class, [
                'label' => 'Publish',
                'data' => true,
                'required' => false
            ])
            ->add('allowComment', CheckboxType::class, [
                'data' => true,
                'required' => false
            ])
            ->add('showHomePage', CheckboxType::class, [
                'data' => false,
                'required' => false
            ])
            ->add('featuredArticle', CheckboxType::class, [
                'data' => false,
                'required' => false
            ]);
    }

}
