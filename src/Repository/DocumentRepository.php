<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
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
