<?php

declare(strict_types=1);

namespace App\Domain\Order\Form;

use App\Domain\Order\Workflow\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersFilterType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ) {
        $builder
            ->add(
                'search',
                TextType::class,
                [
                    "required" => false,
                ],
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    "required" => false,
                    "choices" => OrderStatus::statuses(),
                ],
            )
            ->add(
                'fromDate',
                DateType::class,
                [
                    "required" => false,
                ],
            )
            ->add(
                'toDate',
                DateType::class,
                [
                    "required" => false,
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
        ]);
    }
}
