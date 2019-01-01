<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route(name="app_admin_article_new", path="/admin/article/new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $data */
            $article = $form->getData();
            $article->setAuthor($this->getUser());
            $article->setImageFilename('asteroid.jpeg');
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article created !');

            return $this->redirectToRoute('app_admin_article_list');
        }

        return $this->render('article_admin/new.html.twig', ['articleForm' => $form->createView()]);
    }

    /**
     * @Route(name="app_admin_article_edit", path="/admin/article/{id}/edit")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     *
     * @param Article                $article
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $data */
            $article = $form->getData();
            $article->setAuthor($this->getUser());
            $article->setImageFilename('asteroid.jpeg');
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article updated !');

            return $this->redirectToRoute('app_admin_article_edit', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article_admin/edit.html.twig', ['articleForm' => $form->createView()]);
    }

    /**
     * @Route(name="app_admin_article_list", path="/admin/article")
     *
     * @param ArticleRepository $articleRepository
     *
     * @return Response
     */
    public function list(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('article_admin/list.html.twig', ['articles' => $articles]);
    }
}
