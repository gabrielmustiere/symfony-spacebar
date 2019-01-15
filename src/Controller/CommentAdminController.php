<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentAdminController.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class CommentAdminController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment_index")
     *
     * @param CommentRepository  $repository
     * @param Request            $request
     * @param PaginatorInterface $paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CommentRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $pagination = $paginator->paginate(
            $repository->getWithSearchQueryBuilder($request->query->get('q')),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('comment_admin/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
