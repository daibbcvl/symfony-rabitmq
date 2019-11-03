<?php

namespace App\Controller\BackEnd;

use App\Entity\City;
use App\Entity\Document;
use App\Entity\Post;
use App\Form\Type\DestinationType;
use App\Form\Type\DocumentType;
use App\Repository\CityRepository;
use App\Repository\DocumentRepository;
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
 * @Route("/admin/documents")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("", name="document_index", methods={"GET"})
     *
     * @param Request        $request
     * @param DocumentRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, DocumentRepository $repository): Response
    {
        $criteria = [];
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return $this->render('backend/document/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/create", name="document_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('url')->getData();

            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('document_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:'.$e->getMessage());
                }
                $document->setUrl($fileName);
            }
            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', 'Create document successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('document_create');
        }

        return $this->render('backend/document/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="destination_edit", methods={"GET", "PUT"})
     *
     *
     * @param Request                $request
     * @param Document               $document
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @return Response
     */
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {

        $form = $this->createForm(DocumentType::class, $document, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('url')->getData();
            $fileName = $entityManager->getUnitOfWork()->getOriginalEntityData($document)['url'];
            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('document_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:'.$e->getMessage());
                }
            };
            $document->setUrl($fileName);
            $entityManager->flush();
            $this->addFlash('success', 'Edit document successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/document/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $document,
        ]);
    }
}
