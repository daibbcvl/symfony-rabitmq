<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use function foo\func;
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
    const MYSQL_SEARCH_SCORE = 0.09;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getHomePageArticles($limit = 5)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->where('p.showHomePage = true')
            ->andWhere("p.type = 'post'");
        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
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
     * @param     Tag $tag
     * @param int     $limit
     * @return array
     */
    public function getPostsByTag(Tag $tag, $limit = 20)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $orX = $queryBuilder->expr()->orX();
        $orX->add(':tag MEMBER OF p.tags');
        $queryBuilder
            ->leftJoin('p.tags', 'tags')
            ->andWhere($orX)->setParameter('tag', $tag);

        return $queryBuilder->getQuery()->setMaxResults($limit)->getArrayResult();
    }

    public function searchPostByTitleAndType(string $title)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->andWhere('MATCH_AGAINST(p.title, :title) > :score')
            ->setParameter('title', $title)
            ->setParameter('score', self::MYSQL_SEARCH_SCORE);

        return $queryBuilder->getQuery()->getArrayResult();
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

        if (isset($criteria['categorySlug'])) {
            $queryBuilder->join('p.category', 'category')
                ->andWhere('category.categorySlug = :slug')
                ->setParameter('slug', $criteria['categorySlug']);
            unset($criteria['categorySlug']);
        }

        foreach ($criteria as $field => $value) {
            if (null !== $value) {
                $queryBuilder->andWhere("p.$field = :$field")->setParameter($field, $value);
            }
        }

        $adapter = new DoctrineORMAdapter($queryBuilder);

        return new Pagerfanta($adapter);
    }

    /**
     * @param City $city
     * @return mixed
     */
    public function getPostByCity($city)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder
            ->leftJoin('p.city', 'city')
            ->where('p.city  = :city')->setParameter('city', $city);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function resetFeaturedArticles(int $id)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query = $queryBuilder->update()
            ->set('p.featuredArticle', '?1')
            ->where('p.id != ?2')
            ->setParameter(1, false)
            ->setParameter(2, $id)
            ->getQuery();

        return $query->execute();
    }

    public function getFeaturedArticle()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl'])
            ->where('p.featuredArticle = true');

        return \count($queryBuilder->getQuery()->getResult()) ? $queryBuilder->getQuery()->getSingleResult() : null;
    }

    public function getPostByCategory($category, $limit)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->where('p.category = :category')->setParameter('category', $category)
            ->andWhere("p.type = 'post'");
        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
    }

    public function getArticleDetailsById(Post $post)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.content', 'p.lang', 'p.allowComment', 'p.commentCount', 'p.viewerCount', 'p.titleSeo', 'p.meta', 'p.keyword', 'p.publishedAt'])
            ->where('p.id = :id')->setParameter('id', $post);
        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * GET article same tag
     * @param Post $post
     * @param      $limit
     * @return array|mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getRelatedArticles(Post $post, $limit)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT post_id FROM post_tag 
        WHERE tag_id IN (:tags) AND post_id != :id 
        ORDER BY post_id ASC LIMIT $limit";
        $stmt = $conn->prepare($sql);
        $tags = [];
        foreach ($post->getTags() as $tag) {
            $tags [] = $tag->getId();
        }
        $stmt->execute([
            'tags' => implode(',', $tags),
            'id' => $post->getId()
        ]);

        $ids = $stmt->fetchAll();
        if (!\count($ids)) {
            return [];
        }
        $postIds = [];
        foreach ($ids as $id) {
            $postIds[] = $id['post_id'];
        }

        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select(['p.id', 'p.title', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->where('p.id IN (:ids)')->setParameter('ids', $postIds);
        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
    }

    /**
     * GET list article same category
     * @param Post $post
     * @param      $limit
     * @return array|mixed
     */
    public function getArticlesInSameCategory(Post $post, $limit)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select(['p.id', 'p.title', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->where('p.id != :id')->setParameter('id', $post->getId())
            ->andWhere('p.category = :category')->setParameter('category', $post->getCategory());

        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
    }

    public function getPopularArticles($limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->where('p.popularArticle = true')
            ->andWhere("p.type = 'post'");
        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
    }

    public function getLatestArticles($limit = 5)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.slug', 'p.createdAt', 'p.publishedAt'])
            ->andWhere("p.type = 'post'")
            ->orderBy('p.publishedAt', 'DESC');
        return $queryBuilder->getQuery()->setMaxResults($limit)->getResult();
    }

    /**
     * @param array $criteria
     * @param array $sort
     *
     * @return Pagerfanta
     */
    public function searchABC(array $criteria, array $sort): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('p');
        //$queryBuilder->select(['p.id', 'p.title', 'p.summary', 'p.thumbUrl', 'p.slug', 'p.createdAt', 'p.publishedAt']);


        $adapter = new DoctrineORMAdapter($queryBuilder);

        return new Pagerfanta($adapter);
    }
}
