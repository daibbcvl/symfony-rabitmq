<?php

namespace App\EventListener;

use App\Entity\Post;
use App\Entity\PostView;
use App\Event\PostEvent;

use App\Repository\PostViewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PostSubscriber implements EventSubscriberInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PostViewRepository */
    private $postViewRepository;

    /**
     * PostSubscriber constructor.
     * @param LoggerInterface        $logger
     * @param EntityManagerInterface $entityManager
     * @param PostViewRepository     $postViewRepository
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, PostViewRepository $postViewRepository)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->postViewRepository = $postViewRepository;

    }


    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PostEvent::VIEW => 'countView',
        ];
    }


    public function countView(PostEvent $event)
    {
        $post = $event->getPost();
        $ip = $event->getIp();

        $count = $this->postViewRepository->hasSessionIp($post->getId(), $ip);
        if (!$count) {
            $post->setViewerCount($post->getViewerCount() + 1);
            $postView = new PostView();
            $postView->setIp($ip)->setPost($post);
            $this->entityManager->persist($postView);
            $this->entityManager->flush();
        }
    }
}
