<?php

namespace App\Controller\FrontEnd;

use App\Repository\PostRepository;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     *
     * @param PostRepository $postRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $postRepository)
    {
        //$posts = $postRepository->getHomePageArticles();

        //var_dump(count($posts)); die;

        return $this->render('front/default/index.html.twig', [
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

    /**
     * @Route("/send", name="send")
     *
     * @param ProducerInterface $messageProducer
     */
    public function send(ProducerInterface $messageProducer)
    {
        $msg = ['user_id' => 1235, 'message' => 'Hello World'];
        $messageProducer->publish(serialize($msg));

        echo 'send message'.serialize($msg);
        die;
    }
}
