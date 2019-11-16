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
use App\Repository\DocumentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{

    /**
     * @Route("/api/document/home-page", name="api_document_home_page")
     *
     * @param DocumentRepository $documentRepository
     * @param Request            $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listHomePage(DocumentRepository $documentRepository, Request $request)
    {
        $limit = $request->get('limit', 10);
        $response = $this->json($this->toJsonSerializable($documentRepository->findBy(['showHomePage'=>true], [], $limit)));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
