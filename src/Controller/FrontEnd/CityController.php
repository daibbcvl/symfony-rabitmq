<?php

namespace App\Controller\FrontEnd;

use App\Entity\City;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    private $categories;
    private $tags;

    public function __construct(CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->categories = $categoryRepository->findAll();
        $this->tags = $tagRepository->findAll();
    }

    /**
     * @Route("/city/{slug}", name="city_details")
     *
     * @param Request                $request
     * @param City                   $city
     * @param EntityManagerInterface $manager
     * @param CommentRepository      $commentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, City $city, EntityManagerInterface $manager, PostRepository $postRepository)
    {

        return $this->render('front/city/show.html.twig', [
            'city' => $city,
            'posts' => $postRepository->getPostByCity($city),
            'categories' => $this->categories,
            'tags' => $this->tags,
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }
}
