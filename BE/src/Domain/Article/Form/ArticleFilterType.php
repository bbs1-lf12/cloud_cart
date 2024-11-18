<?php

declare(strict_types=1);

namespace App\Domain\Article\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                NumberType::class,
                [
                    'required' => false,
                    'attr' => [
                        'type' => 'number',
                        'step' => 'any',
                        'min' => '0',
                        'pattern' => '\d',
                        'value' => '',
                    ],
                ],
            )
            ->add(
                'priceTo',
                NumberType::class,
                [
                    'required' => false,
                    'attr' => [
                        'type' => 'number',
                        'step' => 'any',
                        'min' => '0',
                        'pattern' => '\d',
                        'value' => '',
                    ],
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

        $builder->get('available')
            ->addModelTransformer($this->intToBool())
        ;
        $builder->get('isFeatured')
            ->addModelTransformer($this->intToBool())
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
            ) => intval($price) * 100,
            fn (
                $price,
            ) => $price / 100,
        );
    }

    public function intToBool(): CallbackTransformer
    {
        return new CallbackTransformer(
            fn (
                $bool,
            ) => boolval($bool),
            fn (
                $bool,
            ) => boolval($bool),
        );
    }
}
