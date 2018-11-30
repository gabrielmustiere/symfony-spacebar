<?php

/** @noinspection PhpUnusedLocalVariableInspection */
/** @noinspection PhpUnusedLocalVariableInspection */

/** @noinspection PhpUnusedLocalVariableInspection */

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function json_encode;
use function sprintf;

/**
 * Class ArticleController.
 */
class ArticleController extends AbstractController
{
    /** @var bool */
    private $isDebug;

    /**
     * ArticleController constructor.
     *
     * @param bool $isDebug
     */
    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route(name="app_homepage", path="/")
     *
     * @param ArticleRepository $repository
     *
     * @return Response
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByNewest();

        return $this->render('article/homepage.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route(name="article_show", path="/news/{slug}")
     *
     * @param Request     $request
     * @param SlackClient $slack
     * @param Article     $article
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, SlackClient $slack, Article $article)
    {
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $request->getRequestUri()));
        }

        if ($article->getSlug()) {
            $slack->sendMessage('Symfony', 'Ah, Kirk, my old friend...');
        }

        $comments = $article->getComments();

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route(name="article_toogle_heart", path="/news/{slug}/heart", methods={"POST"})
     *
     * @param Article                $article
     * @param LoggerInterface        $logger
     * @param EntityManagerInterface $entityManager
     *
     * @return JsonResponse
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $article->incrementHeartCount();
        $entityManager->persist($article);
        $entityManager->flush();

        $logger->info(sprintf('Article is being hearted : %1', json_encode($article->getSlug())));

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}
