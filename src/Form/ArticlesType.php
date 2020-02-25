<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, [
                'label'       => 'Ваше имя',
                'mapped'      => false,
                'attr'        => ['class' => 'form-control'],
                'constraints' => [ new NotBlank([ 'message'         => 'Введите Ваше имя!' ]),
                    new Length([
                        'max'             => 50,
                        'maxMessage'      => 'Длина имени не должна превышать 50 символов!',
                        'min'             => 2,
                        'minMessage'      => 'Длина имени не должна быть меньше 2 символов!'
                         ])
                    ]

            ])
            ->add('title', TextType::class, ['label' => 'Название', 'attr' => ['class' => 'form-control']])
            ->add('artText', TextareaType::class, ['label' => 'Статья', 'attr' => ['class' => 'form-control']])
            ->add('tags', TextType::class, [
                'label'       => 'Теги',
                'mapped'      => false,
                'help'        => '(Укажите теги через пробел или запятую)',
                'attr'        => ['class' => 'form-control'],
                'constraints' => [ new NotBlank([ 'message'         => 'Введите теги!' ]),
                    new Length([
                        'max'             => 50,
                        'maxMessage'      => 'Длина имени не должна превышать 50 символов!',
                        'min'             => 2,
                        'minMessage'      => 'Длина имени не должна быть меньше 2 символов!'
                    ])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Опубликовать'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
