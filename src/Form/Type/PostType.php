<?php

namespace App\Form\Type;


use App\Entity\Category;
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
                //'widget' => 'select2',
                'class' => Category::class,
//                'placerequired' => false
            ])
            ->add('title', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class'=> 'slug-title']
            ])

//            ->add('content', TextareaType::class, [
//                'constraints' => new NotBlank(),
//            ])
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                    'entities_latin' => false
                    //...
                )))


            ->add('meta', TextType::class, [
            ])
            ->add('keyword', TextType::class, [
            ])
            ->add('titleSeo', TextType::class, [
            ])

            ->add('thumbUrl', FileType::class, [
                'data' => null
            ])

        ->add('slug', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class'=> 'slug']
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
            ->add('featuredArtitcle', CheckboxType::class, [
                'data' => false,
                'required' => false
            ])


        ;
    }

}
