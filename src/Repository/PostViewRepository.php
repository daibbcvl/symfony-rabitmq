<?php

namespace App\Repository;

use App\Entity\PostView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostView[]    findAll()
 * @method PostView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostViewRepository extends ServiceEntityRepository
{
    const DEFAULT_DURATION = 15;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostView::class);
    }

    public function hasSessionIp($postId, $ip)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->select('count(p.id)')
            ->where('p.ip = :ip')
            ->andWhere('p.post = :postId')
            ->andWhere('TIMESTAMPDIFF(MINUTE, p.createdAt, CURRENT_TIMESTAMP()) <= ' . self::DEFAULT_DURATION)
            ->setParameter('ip', $ip)
            ->setParameter('postId', $postId);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
