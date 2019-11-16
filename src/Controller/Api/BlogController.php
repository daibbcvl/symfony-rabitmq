<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    private $categories;
    private $tags;

    public function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->categories = $categoryRepository->findAll();
        $this->tags = $tagRepository->findAll();
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
        return $this->json($this->toJsonSerializable($postRepository->getFeaturedArticle()));
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
        return $this->json($this->toJsonSerializable($postRepository->getPopularArticles()));
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
        return $this->json($this->toJsonSerializable($postRepository->getHomePageArticles()));
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
        return $this->json($this->toJsonSerializable($postRepository->getLatestArticles()));
    }
    
    /**
     * @Route("/api/post/{slug}", name="api_article_details")
     *
     * @param string         $slug
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function details(string $slug, PostRepository $postRepository)
    {
        return $this->json($this->toJsonSerializable($postRepository->getArticleDetailsBySlug($slug)));
    }

    /**
     * @Route("/api/post/related-aticle/{id}", name="api_relayed_article")
     *
     * @param Post           $post
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function related(Post $post, PostRepository $postRepository, Request $request)
    {
        $limit = $request->get('limit', 10);
        return $this->json($this->toJsonSerializable($postRepository->getRelatedArticles($post, $limit)));
    }

}
