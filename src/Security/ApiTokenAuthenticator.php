<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class ApiTokenAuthenticator.
 */
class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var ApiTokenRepository */
    private $apiTokenRepository;

    /**
     * ApiTokenAuthenticator constructor.
     *
     * @param ApiTokenRepository $apiTokenRepository
     */
    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->get('Authorization') && 0 === mb_strpos($request->headers->get('Authorization'), 'Bearer');
    }

    /**
     * @param Request $request
     *
     * @return mixed|voi
     */
    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return mb_substr($authorizationHeader, 7);
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @throws \Exception
     *
     * @return User|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials,
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token is expired');
        }

        return $token->getUser();
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool|void
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey(),
        ], 401);
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return \Symfony\Component\HttpFoundation\Response|void|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new \Exception('Not used: entry_point from other authentication is used');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
