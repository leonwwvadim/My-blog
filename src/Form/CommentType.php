<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentTxt', TextareaType::class, [
                'label'         => 'Ваш коментарий',
                'attr'          => ['class' => 'form-control']
                ])
            ->add('user', EntityType::class, [
                'class'         => User::class,
                'label'         => 'Ваше имя',
                'attr'          => ['class' => 'form-control'],
                'choices'       => $this->userRepository->userSortAsc(),
                'placeholder'   => 'Найди себя',
                'constraints'   => [
                    new NotBlank([ 'message'  => 'Выберите Ваше имя!' ])]
                ])
            ->add('save', SubmitType::class, ['label' => 'Готово'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
