<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Model\Article;
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

        $domesticCategoryResult = new DomesticCategory($domesticCategory->getName(), $domesticCategory->getCategorySlug());
        $north = new CategoryItem($northCategory->getName());
        $north->setSlug($northCategory->getCategorySlug());
        $north->setPosts($postRepository->getPostByCategory($northCategory, 3));

        $middle = new CategoryItem($middleCategory->getName());
        $middle->setPosts($postRepository->getPostByCategory($middleCategory, 3));
        $middle->setSlug($middleCategory->getCategorySlug());

        $south = new CategoryItem($southCategory->getName());
        $south->setPosts($postRepository->getPostByCategory($southCategory, 3));
        $south->setSlug($southCategory->getCategorySlug());

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
        $domesticCategory = $categoryRepository->findOneByCategorySlug('di-nuoc-ngoai');
        $northCategory = $categoryRepository->findOneByCategorySlug('chau-a');
        $middleCategory = $categoryRepository->findOneByCategorySlug('chau-au');
        $southCategory = $categoryRepository->findOneByCategorySlug('chau-my');
        $africaCategory = $categoryRepository->findOneByCategorySlug('chau-phi');
        $australiaCategory = $categoryRepository->findOneByCategorySlug('chau-uc');

        $domesticCategoryResult = new DomesticCategory($domesticCategory->getName(), $domesticCategory->getCategorySlug());
        $north = new CategoryItem($northCategory->getName());
        $north->setSlug($northCategory->getCategorySlug());
        $north->setPosts($postRepository->getPostByCategory($northCategory, 3));

        $middle = new CategoryItem($middleCategory->getName());
        $middle->setPosts($postRepository->getPostByCategory($middleCategory, 3));
        $middle->setSlug($middleCategory->getCategorySlug());

        $south = new CategoryItem($southCategory->getName());
        $south->setPosts($postRepository->getPostByCategory($southCategory, 3));
        $south->setSlug($southCategory->getCategorySlug());

        $south = new CategoryItem($southCategory->getName());
        $south->setPosts($postRepository->getPostByCategory($southCategory, 3));
        $south->setSlug($southCategory->getCategorySlug());

        $africa = new CategoryItem($africaCategory->getName());
        $africa->setPosts($postRepository->getPostByCategory($africaCategory, 3));
        $africa->setSlug($africaCategory->getCategorySlug());

        $australia = new CategoryItem($australiaCategory->getName());
        $australia->setPosts($postRepository->getPostByCategory($australiaCategory, 3));
        $australia->setSlug($australiaCategory->getCategorySlug());

        $domesticCategoryResult->addItem($north);
        $domesticCategoryResult->addItem($middle);
        $domesticCategoryResult->addItem($south);
        $domesticCategoryResult->addItem($africa);
        $domesticCategoryResult->addItem($australia);

        $response = $this->json($this->toJsonSerializable($domesticCategoryResult));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }


    /**
     * @Route("/api/categories/{categorySlug}", name="api_category")
     *
     * @param Category       $category
     * @param PostRepository $postRepository
     *
     * @param Request        $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function category(Category $category, PostRepository $postRepository, Request $request)
    {
        $result = $this->getArea($category->getCategorySlug(), $request, $postRepository);
        $response = $this->json($this->toJsonSerializable($result));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    private function getArea(string $slug, Request $request, PostRepository $postRepository)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 3);
        $sort = $request->get('sort', []);
        $pager = $postRepository->search(['categorySlug' => $slug], $sort)->setMaxPerPage($size)->setCurrentPage($page);
        $items = [];
        foreach ($pager->getIterator() as $page) {
            $article = new Article($page);
            $article->minimizeAttributes(['summary' => true]);
            $items[] = $article;
        }

        $result = [];
        $result['nbResults'] = $pager->getNbResults();
        $result['haveToPaginate'] = $pager->haveToPaginate();
        $result['hasPreviousPage'] = $pager->hasPreviousPage();
        $result['previousPage'] = $pager->hasPreviousPage() ? $pager->getPreviousPage() : null;
        $result['hasNextPage'] = $pager->hasNextPage();
        $result['nextPage'] = $pager->hasNextPage() ? $pager->getNextPage() : null;
        $result['currentPageOffsetStart'] = $pager->getCurrentPageOffsetStart();
        $result['currentPageOffsetEnd'] = $pager->getCurrentPageOffsetEnd();
        $result['items'] = $items;

        return $result;
    }

}
