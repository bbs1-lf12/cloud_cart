<?php

declare(strict_types=1);

namespace App\Domain\Article\Form;

use App\Domain\Options\Service\OptionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFilterType extends AbstractType
{
    public function __construct(
        private readonly OptionService $optionService,
    ) {
    }

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
                        'pattern' => '^\d+(\.\d+)?$',
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
                        'pattern' => '^\d+(\.\d+)?$',
                    ],
                ],
            )
            ->add(
                'reset',
                ButtonType::class,
                [
                    'label' => 'Reset',
                    'attr' => [
                        'class' => 'resetFilter button button-warning',
                    ],
                ],
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Search',
                    'attr' => [
                        'class' => 'button button-accent',
                    ],
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

        if ($options['FO'] === false) {
            $builder
                ->add(
                    'isEnabled',
                    CheckboxType::class,
                    [
                        'required' => false,
                        'label' => 'Enabled',
                    ],
                )
                ->add(
                    'isFeatured',
                    CheckboxType::class,
                    [
                        'required' => false,
                        'label' => 'Featured',
                    ],
                )
            ;

            $builder->get('isEnabled')
                ->addModelTransformer($this->intToBool())
            ;
            $builder->get('isFeatured')
                ->addModelTransformer($this->intToBool())
            ;

            if ($this->optionService->getOptions()
                ->getLowStockNotification()) {
                $builder
                    ->add(
                        'lowStock',
                        CheckboxType::class,
                        [
                            'required' => false,
                            'label' => 'Low stock',
                        ],
                    )
                ;

                $builder->get('lowStock')
                    ->addModelTransformer($this->intToBool())
                ;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'FO' => false,
        ]);
    }

    public function priceToCents(): CallbackTransformer
    {
        return new CallbackTransformer(
            fn (
                $price,
            ) => intval($price),
            fn (
                $price,
            ) => intval($price),
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
