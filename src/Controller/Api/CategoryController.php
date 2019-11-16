<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Model\CategoryItem;
use App\Model\DomesticCategory;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/api/categories/domestic", name="api_category_domestic")
     *
     * @param PostRepository     $postRepository
     *
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function domestic(PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $domesticCategory = $categoryRepository->findOneByCategorySlug('di-trong-nuoc');
        $northCategory = $categoryRepository->findOneByCategorySlug('mien-bac');
        $middleCategory = $categoryRepository->findOneByCategorySlug('mien-trung');
        $southCategory = $categoryRepository->findOneByCategorySlug('mien-nam');

        $domesticCategoryResult = new DomesticCategory($domesticCategory->getName());
        $north = new CategoryItem($northCategory->getName());
        $north->setPosts($postRepository->getPostByCategory($northCategory, 3));

        $middle = new CategoryItem($middleCategory->getName());
        $middle->setPosts($postRepository->getPostByCategory($middleCategory, 3));

        $south = new CategoryItem($southCategory->getName());
        $south->setPosts($postRepository->getPostByCategory($southCategory, 3));

        $domesticCategoryResult->addItem($north);
        $domesticCategoryResult->addItem($middle);
        $domesticCategoryResult->addItem($south);

        $response = $this->json($this->toJsonSerializable($domesticCategoryResult));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/api/categories/abroad", name="api_category_abroad")
     *
     * @param PostRepository     $postRepository
     *
     * @param CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function abroad(PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $abroadCategory = $categoryRepository->findOneByCategorySlug('di-nuoc-ngoai');

        $abroad = new CategoryItem($abroadCategory->getName());
        $abroad->setPosts($postRepository->getPostByCategory($abroadCategory, 3));

        $response = $this->json($this->toJsonSerializable($abroad));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }


}
