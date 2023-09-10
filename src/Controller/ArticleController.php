<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/article')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private TagRepository $tagRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'article_index', methods: ['GET'])]
    public function index(): Response
    {
        $articles = $this->articleRepository->findAllWithTags();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $tag = $this->tagRepository->findAll();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'tag' => $tag
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/rss/{id}', name: 'article_rss_show', methods: ['GET'])]
    public function showRSS(int $id): Response
    {
        $rss = simplexml_load_file('https://www.nasa.gov/rss/dyn/breaking_news.rss');
        // On récupère des unités de flux via channel Item
        $rssItem = $rss->channel->item[intval($id)];
        dump($rss);
        dump($id);
        
        return $this->render('article/show_rss.html.twig', [
            'rssItem' => $rssItem,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response|RedirectResponse
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/supprimer/{id}', name: 'article_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Article $article): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }
        // $this->articleRepository->remove($article, true);
        return $this->redirectToRoute('article_index');
    }
}
