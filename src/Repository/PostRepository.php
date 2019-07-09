<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getHomePageArticles($limit = 4)
    {
        return $this->findBy(['showHomePage' => true, 'type' => 'post'], ['publishedAt' => 'ASC'], $limit);
    }

    public function getArchiveArticles($year, $month, $limit = 20)
    {

        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->where('YEAR(p.publishedAt) = :year')
                    ->andWhere('MONTH(p.publishedAt) =:month')
                    ->setParameter('year', $year)
                    ->setParameter('month', $month)
                   ->orderBy('p.publishedAt', 'ASC');

        return $queryBuilder->getQuery()->setMaxResults($limit)->getArrayResult();
    }

    /**
     * @param     Tag[] $tags
     * @param int $limit
     * @return array
     */
    public function getPostsByTags($tags, $limit = 20)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $orX = $queryBuilder->expr()->orX();
        $orX->add(':tag MEMBER OF p.tags');
        $queryBuilder
            ->leftJoin('p.tags', 'tags')
            ->andWhere($orX)->setParameter('tags', $tags);

        return $queryBuilder->getQuery()->setMaxResults($limit)->getArrayResult();
    }

    /**
     * @param array $criteria
     * @param array $sort
     *
     * @return Pagerfanta
     */
    public function search(array $criteria, array $sort): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($criteria as $field => $value) {
            if (null !== $value) {
                $queryBuilder->andWhere("p.$field = :$field")->setParameter($field, $value);
            }
        }
        $adapter = new DoctrineORMAdapter($queryBuilder);

        return new Pagerfanta($adapter);
    }
}
