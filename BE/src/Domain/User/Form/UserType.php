<?php

declare(strict_types=1);

namespace App\Domain\User\Form;

use App\Domain\User\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'email',
                EmailType::class,
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'required' => !$options['editMode'],
                    'empty_data' => $options['editMode'] ? '' : null,
                ],
            )
            ->add(
                'billingAddress',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Billing address',
                ],
            )
            ->add(
                'shippingAddress',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Shipping address',
                ],
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $options['editMode']
                        ? 'Update'
                        : 'Create',
                    'attr' => [
                        'class' => 'button button-accent',
                    ],
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'editMode' => false,
            ],
        );
    }
}
