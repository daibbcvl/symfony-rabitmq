<?php

namespace App\Controller\FrontEnd;

use App\Entity\Post;
use App\Form\Site\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    private $categories;
    private $tags;

    function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
            $this->categories = $categoryRepository->findAll();
            $this->tags = $tagRepository->findAll();
    }

    /**
     * @Route("/", name="default")
     * @param PostRepository $postRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $postRepository)
    {
        return $this->render('front/blog/index.html.twig', [
            'posts' => $postRepository->getHomePageArticles(),
            'categories' => $this->categories,
            'tags' => $this->tags,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_details")
     * @param Request $request
     * @param Post    $post
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function show(Request $request, Post $post)
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        return $this->render('front/blog/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'categories' => $this->categories,
            'tags' => $this->tags,
        ]);
    }
}
