<?php

namespace App\Form\Site;


use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'commentAuthor' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'constraints' => new NotBlank(),
                'label' => 'Nội dung bình luận'
            ]);

        if (!$options['commentAuthor']) {
            $builder
                ->add('name', TextType::class, [
                    'constraints' => new NotBlank(),
                    'label' => 'Tên'
                ])
                ->add('email', TextType::class, [
                    'constraints' => new NotBlank()
                ]);
        }
    }
}
