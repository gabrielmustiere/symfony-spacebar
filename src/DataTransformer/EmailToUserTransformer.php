<?php

namespace App\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class EmailToUserTransformer.
 */
class EmailToUserTransformer implements DataTransformerInterface
{
    /** @var UserRepository */
    private $userRepository;

    /** @var callable $finderCallback */
    private $finderCallback;

    /**
     * EmailToUserTransformer constructor.
     *
     * @param UserRepository $userRepository
     * @param callable       $finderCallback
     */
    public function __construct(UserRepository $userRepository, callable $finderCallback)
    {
        $this->userRepository = $userRepository;
        $this->finderCallback = $finderCallback;
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
        if (!$value) {
            return;
        }
        
        $callBack = $this->finderCallback;
        $user = $callBack($this->userRepository, $value);

        if (!$user) {
            throw new TransformationFailedException(sprintf('No user found with email "%s"', $value));
        }

        return $user;
    }
}
