<?php

declare(strict_types=1);

namespace App\Domain\Options\Form;

use App\Domain\Options\Entity\Options;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class OptionsType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ) {
        $builder
            ->add(
                'appName',
                TextType::class,
                [
                    'required' => true,
                ],
            )
            ->add(
                'appLogo',
                FileType::class,
                [
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new Image(
                            [
                                'maxSize' => '5M',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/jpg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid image file (jpg, jpeg, png).',
                            ],
                        ),
                    ],
                    'attr' => [
                        'accept' => 'image/jpeg, image/jpg, image/png',
                    ],
                ],
            )
            ->add(
                'lowStockNotification',
                NumberType::class,
                [
                    'required' => false,
                    'label' => 'Stock limit',
                    'attr' => [
                        'pattern' => '\d*',
                    ],
                ],
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Save',
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
                'data_class' => Options::class,
            ],
        );
    }
}
