<?php

namespace App\Form\Type;

use App\Entity\Continent;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CountryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('code', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('continent', ChoiceType::class, [
                'required' => false,
                'choices' => $this->getContinents(),
            ]);
    }

    /**
     * @return array
     */
    private function getContinents(): array
    {
        $continents = [];
        foreach (Continent::getAll() as $continent) {
            $continents[$continent] = $continent;
        }

        return $continents;
    }
}
