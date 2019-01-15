<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ArticleVoter.
 */
class ArticleVoter extends Voter
{
    /** @var Security */
    private $security;

    /**
     * ArticleVoter constructor.
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return \in_array($attribute, ['MANAGE'], true) && $subject instanceof Article;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        /** @var Article $subject */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'MANAGE':
                if ($subject->getAuthor() === $user) {
                    return true;
                    break;
                }

                if ($this->security->isGranted('ROLE_ADMIN_ARTICLE')) {
                    return true;
                    break;
                }
        }

        return false;
    }
}
