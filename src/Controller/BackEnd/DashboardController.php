<?php

namespace App\Controller\BackEnd;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dashboards")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_index")
     */
    public function index()
    {
        return $this->render('backend/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
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
