<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserAdminController.
 */
class UserAdminController extends AbstractController
{
    /**
     * @Route(name="app_admin_api_users", path="/admin/api/articles")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     *
     * @param UserRepository $userRepository
     * @param Request        $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUsersApi(UserRepository $userRepository, Request $request)
    {
        $users = $userRepository->findAllMatching($request->query->get('query'));

        return $this->json([
            'users' => $users,
        ], 200, [], ['groups' => ['main']]);
    }
}
