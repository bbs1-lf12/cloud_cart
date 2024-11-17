<?php

declare(strict_types=1);

namespace App\Domain\Article\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFilterType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'search',
                TextType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'priceFrom',
                TextType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'priceTo',
                TextType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'available',
                CheckboxType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'isFeatured',
                CheckboxType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Search',
                ],
            )
        ;

        // need to multiply by 100 to convert to cents
        $builder->get('priceFrom')
            ->addModelTransformer(
                $this->priceToCents(),
            )
        ;
        $builder->get('priceTo')
            ->addModelTransformer(
                $this->priceToCents(),
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
    }

    public function priceToCents(): CallbackTransformer
    {
        return new CallbackTransformer(
            fn (
                $price,
            ) => $price * 100,
            fn (
                $price,
            ) => $price / 100,
        );
    }
}
