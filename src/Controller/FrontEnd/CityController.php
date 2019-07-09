<?php

namespace App\Controller\FrontEnd;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
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
     * @param Post                   $post
     * @param EntityManagerInterface $manager
     * @param CommentRepository      $commentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request, Post $post, EntityManagerInterface $manager, CommentRepository $commentRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['commentAuthor' => null !== $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setApproved(Comment::COMMENT_STATE_PENDING);
            if ($user) {
                $comment->setName($user->getFirstName())
                        ->setCommentAuthor($user)
                        ->setEmail($user->getEmail());
            }
            $manager->persist($form->getData());
            $manager->flush();

            return $this->redirect($request->getUri());
        }

        return $this->render('front/blog/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'categories' => $this->categories,
            'tags' => $this->tags,
            'comments' => $commentRepository->getApprovedComments($post),
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }
}