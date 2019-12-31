<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Event\PostEvent;
use App\Model\Article;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    private $categories;
    private $tags;
    private $eventDispatcher;


    public function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->categories = $categoryRepository->findAll();
        $this->tags = $tagRepository->findAll();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/api/post/featured-article", name="api_featured_article")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function featured(PostRepository $postRepository)
    {
        $post = $postRepository->findOneBy(['featuredArticle' => true]);
        $article = new Article($post);
        $response = $this->json($this->toJsonSerializable($article));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/popular-articles", name="api_popular_articles")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function popular(PostRepository $postRepository)
    {
        $results = [];
        $posts = $postRepository->findBy(['type' => 'post', 'popularArticle' => true], ['publishedAt' => 'DESC'], 10);
        foreach ($posts as $post) {
            $article = new Article($post);
            $article->minimizeAttributes(['summary' => true]);
            $results[] = $article;
        }
        $response = $this->json($this->toJsonSerializable($results));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/home-page-aticles", name="api_homepage_article")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function homePage(PostRepository $postRepository)
    {
        $response = $this->json($this->toJsonSerializable($postRepository->getHomePageArticles()));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/latest-aticles", name="api_latest_articles")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function latest(PostRepository $postRepository)
    {
        $results = [];
        $posts = $postRepository->findBy(['type' => 'post'], ['publishedAt' => 'DESC'], 5);
        foreach ($posts as $post) {
            $article = new Article($post);
            $article->minimizeAttributes();
            $results[] = $article;
        }
        $response = $this->json($this->toJsonSerializable($results));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/article/{id}", name="api_article_details")
     *
     * @param Post    $post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function details(Post $post, Request $request)
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');
        $event = new PostEvent($post, $ip);
        $this->eventDispatcher->dispatch(PostEvent::VIEW, $event);

        $article = new Article($post);
        $response = $this->json($this->toJsonSerializable($article));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }


    /**
     * @Route("/api/post/article/{slug}", name="api_article_details")
     *
     * @param Post    $post
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function detailBySlug(Post $post, Request $request)
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');
        $event = new PostEvent($post, $ip);
        $this->eventDispatcher->dispatch(PostEvent::VIEW, $event);

        $article = new Article($post);
        $response = $this->json($this->toJsonSerializable($article));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/related-aticle/{id}", name="api_related_article")
     *
     * @param Post           $post
     * @param PostRepository $postRepository
     *
     * @param Request        $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function related(Post $post, PostRepository $postRepository, Request $request)
    {
        $limit = $request->get('limit', 10);
        $response = $this->json($this->toJsonSerializable($postRepository->getRelatedArticles($post, $limit)));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/post/aticle-same-category/{id}", name="api_article_same_category")
     *
     * @param Post           $post
     * @param PostRepository $postRepository
     *
     * @param Request        $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function articleSameCategory(Post $post, PostRepository $postRepository, Request $request)
    {
        $limit = $request->get('limit', 10);
        $response = $this->json($this->toJsonSerializable($postRepository->getArticlesInSameCategory($post, $limit)));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }


    /**
     * @Route("/api/page/{slug}", name="api_page_about_us")
     *
     * @param string         $slug
     * @param PostRepository $postRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function aboutUs(string $slug, PostRepository $postRepository)
    {
        $post = $postRepository->findOneBy(['slug' => $slug]);
        $article = new Article($post);
        $response = $this->json($this->toJsonSerializable($article));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
