<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    /**
     * CountryRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @param array $criteria
     * @param array $sort
     *
     * @return Pagerfanta
     */
    public function search(array $criteria, array $sort): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('c');
        if (isset($criteria['name'])) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.name LIKE :name')
                ->setParameter('name', "{$criteria['name']}%");
            unset($criteria['name']);
        }
        if (isset($criteria['continents'])) {
            if (\count($criteria['continents'])) {
                $queryBuilder
                    ->andWhere('c.continent IN (:continents)')
                    ->setParameter('continents', $criteria['continents']);
            }
            unset($criteria['continents']);
        }
        if (isset($criteria['tags'])) {
            if (\count($criteria['tags'])) {
                $orX = $queryBuilder->expr()->orX();
                foreach ($criteria['tags'] as $i => $tag) {
                    $orX->add("JSON_CONTAINS(c.tags, :tag_$i) = 1");
                    $queryBuilder->setParameter("tag_$i", json_encode([$tag]));
                }
                $queryBuilder->andWhere($orX);
            }
            unset($criteria['tags']);
        }
        foreach ($criteria as $field => $value) {
            if (null !== $value) {
                $queryBuilder->andWhere("c.$field = :$field")->setParameter($field, $value);
            }
        }
        foreach ($sort as $field => $direction) {
            $queryBuilder = $queryBuilder->orderBy("c.$field", $direction);
        }

        $adapter = new DoctrineORMAdapter($queryBuilder);

        return new Pagerfanta($adapter);
    }
}
