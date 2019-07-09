<?php

namespace App\Controller\FrontEnd;

use App\Form\Site\DestinationSearchType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     *
     * @param Request        $request
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PostRepository $postRepository)
    {
        $form = $this->createForm(DestinationSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('city_details', ['slug' => $form->getData()['city']->getSlug()]);
        }

        return $this->render('front/default/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $postRepository->getHomePageArticles(),
            'cities' => $postRepository->findBy(['type' => 'destination']),
            //'categories' => $this->categories,
            //'tags' => $this->tags,
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }
}
