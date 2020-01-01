<?php

namespace App\Form\Type;

use App\Entity\Document;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Document::class,
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
            ])
            ->add('description', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                    'entities_latin' => false,
                ],])
            ->add('url', FileType::class, [
                'label' => 'Document',
                'attr' => ['accept' => '.pdf,.doc,.docx'],
                'data' => null,
                'required' => false,
            ])
            ->add('coverImage', FileType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('showHomePage', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }
}
