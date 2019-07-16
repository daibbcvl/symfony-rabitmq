<?php

namespace App\Controller\FrontEnd;

use App\Cache\MemcachedHandler;
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
     * @param Request          $request
     * @param PostRepository   $postRepository
     *
     * @param MemcachedHandler $memcachedHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, PostRepository $postRepository, MemcachedHandler $memcachedHandler)
    {
        //$articles = [];
        $articleCache = $memcachedHandler->doRead('HOME_PAGE_ARTICLES');
        if (!$articleCache) {
            $articles = $postRepository->getHomePageArticles();
            $memcachedHandler->doWrite('HOME_PAGE_ARTICLES', $articles);
        } else {
            $articles = $articleCache;
        }

        ///$cities = [];
        $cityCache = $memcachedHandler->doRead('HOME_PAGE_CITIES');
        if (!$articleCache) {
            $cities = $postRepository->findBy(['type' => 'destination']);
            $memcachedHandler->doWrite('HOME_PAGE_CITIES', $cities);
        } else {
            $cities = $cityCache;
        }

        $form = $this->createForm(DestinationSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('city_details', ['slug' => $form->getData()['city']->getSlug()]);
        }

        return $this->render('front/default/index.html.twig', [
            'form' => $form->createView(),
            'posts' => $articles,
            'cities' => $cities,
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
