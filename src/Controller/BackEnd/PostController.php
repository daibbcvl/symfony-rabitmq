<?php

namespace App\Controller\BackEnd;

use App\Entity\Post;
use App\Form\Type\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/posts")
 */
class PostController extends AbstractController
{
    /**
     * @Route("", name="post_index", methods={"GET"})
     *
     * @param Request        $request
     * @param PostRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, PostRepository $repository): Response
    {
        $criteria = [];
        $criteria['type'] = 'post';
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return $this->render('backend/post/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/create", name="post_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param PostRepository         $postRepository
     * @param LoggerInterface        $logger
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository, LoggerInterface $logger): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();

            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:' . $e->getMessage());
                }
                $post->setThumbUrl($fileName);
            }
            $post->setAuthor($this->getUser())->setType('post');
            $entityManager->persist($post);
            $entityManager->flush();
            if ($post->getFeaturedArticle()) {
                $postRepository->resetFeaturedArticles($post->getId());
            }

            $this->addFlash('success', 'Create category successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('post_create');
        }

        return $this->render('backend/post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="post_edit", methods={"GET", "PUT"})
     *
     * @param Request                $request
     * @param Post                   $post
     * @param EntityManagerInterface $entityManager
     *
     * @param PostRepository         $postRepository
     * @param LoggerInterface        $logger
     * @return Response
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, PostRepository $postRepository, LoggerInterface $logger): Response
    {
        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();
            $fileName = $entityManager->getUnitOfWork()->getOriginalEntityData($post)['thumbUrl'];
            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:' . $e->getMessage());
                }
            }
            $post->setThumbUrl($fileName);
            $post->setAuthor($this->getUser());
            $entityManager->flush();
            if ($post->getFeaturedArticle()) {
                $postRepository->resetFeaturedArticles($post->getId());
            }
            $this->addFlash('success', 'Edit post successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="post_delete", methods={"DELETE"})
     *
     * @param Post                   $post
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function delete(Post $post, EntityManagerInterface $entityManager): Response
    {
        $post->setDeleted(true);
        $entityManager->flush();
        $this->addFlash('success', 'Delete post successfully.');

        return $this->redirectToRoute('post_index');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
