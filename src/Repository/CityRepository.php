<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
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

    public function getHomePageDestinations($limit = 4)
    {
        return $this->findBy(['showHomePage' => true],null, $limit);
    }

    public function searchCity(string $name,$id)
    {
        $queryBuilder = $this->createQueryBuilder('ct');
        $queryBuilder
            ->andWhere('MATCH_AGAINST(ct.name, :name) > :score')
            ->setParameter('name', $name)
            ->setParameter('score', 0.09);

        if($id !=null){
            $queryBuilder->andWhere('ct.id = :id')->setParameter('id', $id);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
