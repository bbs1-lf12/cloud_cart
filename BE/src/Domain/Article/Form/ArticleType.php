<?php

declare(strict_types=1);

namespace App\Domain\Article\Form;

use App\Domain\Article\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ) {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                ],
            )
            ->add(
                'description',
                TextType::class,
                [
                    'required' => true,
                ],
            )
            ->add(
                'priceInCents',
                NumberType::class,
                [
                    'required' => true,
                    'attr' => [
                        'type' => 'number',
                        'step' => 'any',
                        'min' => '0',
                        'pattern' => '^\d+(\.\d{1,2})?$',
                    ],
                ],
            )
            ->add(
                'stock',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'type' => 'number',
                        'min' => '0',
                        'pattern' => '^\d+$',
                    ],
                ],
            )
            ->add(
                'imageFile',
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
                'isFeatured',
                CheckboxType::class,
                [
                    'required' => false,
                ],
            )
            ->add(
                'isEnabled',
                CheckboxType::class,
                [
                    'required' => false,
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

        $builder->get('priceInCents')
            ->addModelTransformer(
                new CallbackTransformer(
                    function (
                        $priceInCents,
                    ) {
                        return $priceInCents / 100;
                    },
                    function (
                        $priceInCents,
                    ) {
                        return $priceInCents * 100;
                    },
                ),
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Article::class,
            ],
        );
    }
}
