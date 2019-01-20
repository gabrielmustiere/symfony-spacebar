<?php

namespace App\Form;

use App\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UserSelectTextType.
 */
class UserSelectTextType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /** @var RouterInterface $router */
    private $router;

    /**
     * EmailToUserTransformer constructor.
     *
     * @param UserRepository  $userRepository
     * @param RouterInterface $router
     */
    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->router = $router;
        $this->userRepository = $userRepository;
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer(
            $this->userRepository,
            $options['finder_callback']
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'hum... user not found',
            'finder_callback' => function (UserRepository $userRepository, string $email = null) {
                return $userRepository->findOneBy(['email' => $email]);
            },
            'attr' => [
                'class' => 'js-user-autocomplete',
                'data-autocomplete-url' => $this->router->generate('app_admin_api_users'),
            ],
        ]);
    }
}
