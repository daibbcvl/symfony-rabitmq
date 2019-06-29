<?php

namespace App\Controller\FrontEnd;

use App\Repository\PostRepository;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @param PostRepository $postRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $postRepository)
    {
        return $this->render('front/default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'posts' => $postRepository->getHomePageArticles()
        ]);
    }

    /**
     * @Route("/send", name="send")
     * @param ProducerInterface $messageProducer
     */
    public function send(ProducerInterface $messageProducer)
    {
        $msg = array('user_id' => 1235, 'message' => 'Hello World');
        $messageProducer->publish(serialize($msg));

    	echo "send message". serialize($msg); die;
    }
}
