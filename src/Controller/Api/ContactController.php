<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Form\Site\ContactType;
use App\Model\Article;
use App\Model\CategoryItem;
use App\Model\DomesticCategory;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact")
     *
     * @param Request                $request
     * @param Post                   $post
     * @param EntityManagerInterface $manager
     * @param CommentRepository      $commentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request, Post $post, EntityManagerInterface $manager, CommentRepository $commentRepository)
    {
        $response = new JsonResponse();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            $response->setData([
                'success' => true
            ]);
        }
        else{
            $response->setData([
                'error' => 400,
                'message' => $this->toJsonSerializable($form->getErrors()
            ]);
        }

        return $response;


    }
}
