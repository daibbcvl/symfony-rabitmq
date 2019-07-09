<?php

namespace App\Controller\BackEnd;

use App\Entity\Country;
use App\Form\Type\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/countries")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("", name="country_index", methods={"GET"})
     *
     * @param Request           $request
     * @param CountryRepository $repository
     *
     * @return Response
     */
    public function index(Request $request, CountryRepository $repository): Response
    {
        $criteria = [];
        $page = $request->get('page', 1);
        $size = $request->get('size', 20);
        $sort = $request->get('sort', ['name' => 'asc']);
        $pager = $repository->search($criteria, $sort)->setMaxPerPage($size)->setCurrentPage($page);

        return $this->render('backend/country/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/create", name="country_create", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CountryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Create country successfully.');
            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('country_create');
        }

        return $this->render('backend/country/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="country_edit", methods={"GET", "PUT"})
     *
     * @param Request                $request
     * @param Country                $country
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function edit(Request $request, Country $country, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CountryType::class, $country, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Edit country successfully.');

            if ($url = $request->get('redirect_url')) {
                return $this->redirect($url);
            }
        }

        return $this->render('backend/country/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
