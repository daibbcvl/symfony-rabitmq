<?php

namespace App\Controller\BackEnd;

use App\Entity\City;
use App\Entity\Post;
use App\Form\Type\DestinationType;
use App\Repository\CityRepository;
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
class DestinationController extends AbstractController
{
    /**
     * @Route("", name="destination_index", methods={"GET"})
     *
     * @param Request        $request
     * @param CityRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, CityRepository $repository): Response
    {
        $criteria = [];
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
     * @param LoggerInterface        $logger
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $city = new City();
        $form = $this->createForm(DestinationType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();

            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:'.$e->getMessage());
                }
                $city->setThumbUrl($fileName);
            }
            $entityManager->persist($city);
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
     *
     * @param Request                $request
     * @param City                   $city
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @return Response
     */
    public function edit(Request $request, City $city, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {

        $form = $this->createForm(DestinationType::class, $city, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('thumbUrl')->getData();
            $fileName = $entityManager->getUnitOfWork()->getOriginalEntityData($city)['thumbUrl'];
            if ($file) {
                $fileName = $file->getClientOriginalName();
                try {
                    $file->move($this->getParameter('thumb_dir'), $fileName);
                } catch (FileException $e) {
                    $logger->error('UPLOAD_ERRORS:'.$e->getMessage());
                }
            };
            $city->setThumbUrl($fileName);
            $entityManager->flush();
            $this->addFlash('success', 'Edit city successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/destination/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $city,
        ]);
    }
}
