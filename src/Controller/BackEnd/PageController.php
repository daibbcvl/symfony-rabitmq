<?php

namespace App\Controller\BackEnd;

use App\Entity\Post;
use App\Form\Type\DestinationType;
use App\Form\Type\PageType;
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
 * @Route("/admin/pages")
 */
class PageController extends AbstractController
{
    /**
     * @Route("", name="page_index", methods={"GET"})
     *
     * @param Request        $request
     * @param PostRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, PostRepository $repository): Response
    {
        $criteria = [];
        $criteria['type'] = 'page';
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return $this->render('backend/page/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/create", name="page_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $post = new Post();
        $form = $this->createForm(PageType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */

            $post->setAuthor($this->getUser())->setType('page');
            $post->setType('page');
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Create page successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('page_create');
        }

        return $this->render('backend/page/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="page_edit", methods={"GET", "PUT"})
     *
     * @param Request                $request
     * @param Post                   $post
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     *
     * @return Response
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $form = $this->createForm(PageType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', 'Edit post successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/page/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
}
