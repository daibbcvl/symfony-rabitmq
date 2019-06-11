<?php

namespace App\Controller\BackEnd;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_index")
     */
    public function index()
    {
        //echo "aaaa"; die;
        return $this->render('admin/dashboard/index.html.twig', [
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
