<?php

namespace App\Form\Type;

use App\Entity\Area;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DestinationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'required' => false,
                'class' => Country::class,
                'widget' => 'select2',
            ])
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug-title'],
                'label' => 'Name',
            ])
            ->add('summary', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#22A7F0',
                    'entities_latin' => false,
                ],])
            ->add('content', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                    'entities_latin' => false,
                ],])
            ->add('thumbUrl', FileType::class, [
                'data' => null,
                'required' => false,
            ])
            ->add('area', ChoiceType::class, [
                'required' => false,
                'choices' => $this->getAreas(),
            ])->add('slug', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => ['class' => 'slug'],
            ])
            ->add('publish', CheckboxType::class, [
                'label' => 'Publish',
                'required' => false,
            ])
            ->add('showHomePage', CheckboxType::class, [
                'required' => false,
            ]);
    }

    private function getAreas()
    {
        $areas = ['' => ''];
        foreach (Area::getAll() as $area) {
            $areas[$area] = $area;
        }

        return $areas;
    }
}
