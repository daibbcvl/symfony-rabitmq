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
     * @Route("/api/post/poppular-article", name="api_poppular_article")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(PostRepository $postRepository)
    {
        return $this->json($this->toJsonSerializable($postRepository->getPopularArticle()));
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

}
