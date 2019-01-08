<?php

namespace App\DataTransformer;

use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class EmailToUserTransformer.
 */
class EmailToUserTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * EmailToUserTransformer constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * @param mixed $value The value in the original representation
     *
     * @throws TransformationFailedException when the transformation fails
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }
        if (!$value instanceof User) {
            throw new \LogicException('The UserSelectTextType can only be used with User objects');
        }

        return $value->getEmail();
    }

    /**
     * Transforms a value from the transformed representation to its original.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
        $user = $this->userRepository->findOneBy(['email' => $value]);
        if (!$user) {
            throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
        }

        return $user;
    }
}
