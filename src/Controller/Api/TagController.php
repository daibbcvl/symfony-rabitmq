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

class TagController extends AbstractController
{

    /**
     * @Route("/api/tag/search", name="api_tag_search")
     *
     * @param Request       $request
     * @param TagRepository $repository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function search( Request $request, TagRepository $repository)
    {

        $criteria['name'] = $request->get('name');
        //dd($criteria);

        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        //dd($pager);
        $response = $this->json($this->toJsonSerializable($pager));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;

    }

    /**
     * @Route("/api/tag/{tagSlug}", name="api_tag_slug")
     *
     * @param Tag            $tag
     * @param Request        $request
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function slug(Tag $tag, Request $request, PostRepository $postRepository)
    {
        $tagItem = new \App\Model\Tag();
        $tagItem->setSlug($tag->getTagSlug())->setName($tag->getName());

        $page = $request->get('page', 1);
        $size = $request->get('size', 3);
        $sort = $request->get('sort', []);
        $pager = $postRepository->search(['tag' => $tag], $sort)->setMaxPerPage($size)->setCurrentPage($page);
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

        $response = $this->json($this->toJsonSerializable($result));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }



}
