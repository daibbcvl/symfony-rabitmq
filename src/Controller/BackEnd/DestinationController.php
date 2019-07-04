<?php

namespace App\Controller\BackEnd;

use App\Entity\Country;
use App\Entity\Post;
use App\Form\Type\CountryType;
use App\Form\Type\DestinationType;
use App\Form\Type\PostType;
use App\Repository\CountryRepository;
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
 * @Route("/admin/destinations")
 */
class DestinationController  extends AbstractController
{
    /**
     * @Route("", name="destination_index", methods={"GET"})
     *
     * @param Request        $request
     * @param PostRepository $repository
     * @return Response
     */
    public function index(Request $request, PostRepository $repository): Response
    {
        $criteria = [];
        $criteria['type'] = 'destination';
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return $this->render('backend/destination/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/create", name="destination_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @param LoggerInterface        $logger
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $post = new Post();
        $form = $this->createForm(DestinationType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();

            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir') , $fileName);
                } catch (FileException $e) {
                    $logger->error("UPLOAD_ERRORS:" . $e->getMessage());
                }
                $post->setThumbUrl($fileName);
            }
            $post->setAuthor($this->getUser())->setType('destination');
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Create category successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('destination_create');
        }

        return $this->render('backend/destination/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="destination_edit", methods={"GET", "PUT"})
     *
     * @param Request                $request
     * @param Post                   $post
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @return Response
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $form = $this->createForm(DestinationType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();

            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir') , $fileName);
                } catch (FileException $e) {
                    $logger->error("UPLOAD_ERRORS:" . $e->getMessage());
                }
                $post->setThumbUrl($fileName);
            }
            $post->setAuthor($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', 'Edit post successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/destination/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }
}
