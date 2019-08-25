<?php

namespace App\Controller\FrontEnd;

use App\Form\Site\DestinationSearchType;
use App\Repository\CityRepository;
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
     * @param CityRepository $cityRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PostRepository $postRepository, CityRepository $cityRepository)
    {
        $form = $this->createForm(DestinationSearchType::class, null, [
            'action' => $this->generateUrl('front_search'),
            'method' => 'GET']);
        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            return $this->redirectToRoute('city_details', ['slug' => $form->getData()['city']->getSlug()]);
//        }

        return $this->render('front/default/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $postRepository->getHomePageArticles(),
            'cities' => $cityRepository->getHomePageDestinations(4),
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }

    /**
     * @Route("/search", name="front_search")
     *
     * @param Request        $request
     * @param PostRepository $postRepository
     *
     * @param CityRepository $cityRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request, PostRepository $postRepository, CityRepository $cityRepository)
    {
        $form = $this->createForm(DestinationSearchType::class, null, [
            'action' => $this->generateUrl('front_search'),
            'method' => 'GET']);
        $form->handleRequest($request);


        $posts = [];
        $cities = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $posts = $postRepository->searchPostByTitleAndType($data['keyword']);
            $cities = $cityRepository->searchCity($data['keyword'], $data['city']);
        }
        return $this->render('front/default/search.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
            'cities' => $cities,
        ]);
    }


    /**
     * @Route("/about", name="about")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function about(PostRepository $postRepository)
    {
        $post = $postRepository->findOneBy(['slug' => 'about']);
        return $this->render('front/default/page.html.twig', [

            'post' => $post,
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(PostRepository $postRepository)
    {
        $post = $postRepository->findOneBy(['slug' => 'contact']);
        return $this->render('front/default/page.html.twig', [

            'post' => $post,
            'title' => '',
            'titleSeo' => '',
            'meta' => '',
            'keyword' => '',
            'pageURL' => '',
            'fbPage' => '',
        ]);
    }
}
