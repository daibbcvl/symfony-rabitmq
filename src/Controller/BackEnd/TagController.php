<?php

namespace App\Controller\BackEnd;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tags")
 */
class TagController extends AbstractController
{
    /**
     * @Route(".{_format}", name="tag_index", methods={"GET"}, defaults={"_format": "html"}, requirements={"_format": "html|json"})
     *
     * @param Request       $request
     * @param string        $_format
     * @param TagRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, string $_format, TagRepository $repository): Response
    {
        $criteria = [];
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return 'html' === $_format ? $this->render('backend/tag/index.html.twig', [
            'pager' => $pager,
        ]) : $this->json(($pager));
    }

    /**
     * @Route("/create", name="tag_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Create category successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('tag_create');
        }

        return $this->render('backend/tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="tag_edit", methods={"GET", "PUT"})
     *
     * @param Request                $request
     * @param Tag                    $tag
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Edit tag successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="tag_delete", methods={"DELETE"})
     *
     * @param Tag                    $tag
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function delete(Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $tag->setDeleted(true);
        $entityManager->flush();
        $this->addFlash('success', 'Delete tag successfully.');

        return $this->redirectToRoute('tag_index');
    }
}
