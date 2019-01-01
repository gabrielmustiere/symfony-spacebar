<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleFormType.
 */
class ArticleFormType extends AbstractType
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /**
     * ArticleFormType constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Choose something catchy',
            ])
            ->add('content', TextareaType::class, [
                'help' => 'Write beautiful thing',
            ])
            ->add('publishedAt', null, [
                'help' => 'When do you want to publish the article',
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Choose an author',
                'choices' => $this->userRepository->findAllEmailAlphabetical(),
                'invalid_message' => 'Symfony is too smart for your hacking',
                'choice_label' => function (User $user) {
                    return sprintf('(%d) %s', $user->getId(), $user->getEmail());
                },
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return OptionsResolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
