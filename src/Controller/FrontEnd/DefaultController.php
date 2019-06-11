<?php

namespace App\Controller\FrontEnd;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {

        //var_dump('asdada'); die;
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
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
