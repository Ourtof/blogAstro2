<?php

namespace App\Controller;

use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private TagRepository $tagRepository
    ) {
    }

    #[Route('/menu/article', name: 'menu_article')]
    public function index(Request $request): Response
    {
        $tag = $request->query->get("choose-tag");
        if($tag !== null) {
            $articleArray = $this->articleRepository->getArticleByTag($tag);
        } else {
            $articleArray = $this->articleRepository->findAll();
        }
        $rss = simplexml_load_file('https://www.nasa.gov/rss/dyn/breaking_news.rss');
        // On récupère des unités de flux via channel Item
        $rssItems = $rss->channel->item;
        $tagArray = $this->tagRepository->findAll();
        return $this->render('menu_article/index.html.twig', [
            "tagArray" => $tagArray,
            "articleArray" => $articleArray,
            "rssItems" => $rssItems
        ]);
    }

    #[Route('/menu/article/get-article-AJAX', name: 'menu-article-get-article-ajax')]
    public function getArticleByTag(Request $request) 
    {
        $data = json_decode($request->getContent());

        $articleArray = $this->articleRepository->getArticleByTag($data->tag);

        return new JsonResponse($articleArray);
    }
}
