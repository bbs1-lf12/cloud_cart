<?php

declare(strict_types=1);

namespace App\Domain\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
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
                'roles',
                ChoiceType::class,
                [
                    'required' => false,
                    'choices' => [
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                    ],
                ],
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'reset',
                ButtonType::class,
                [
                    'label' => 'Reset',
                    'attr' => [
                        'class' => 'resetFilter button button-warning'
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Search',
                    'attr' => [
                        'class' => 'button button-primary'
                    ]
                ],
            )
        ;

        $builder->get('isActive')
            ->addModelTransformer($this->intToBool())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
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
