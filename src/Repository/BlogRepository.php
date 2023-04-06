<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function add(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(Request $request)
    {
        $qb = $this->createQueryBuilder('blog')
            ->select('blog', 'ageBetween', 'category', 'status')
            ->innerJoin('blog.ageBetween', 'ageBetween')
            ->innerJoin('blog.category', 'category')
            ->innerJoin('blog.status', 'status');

        if ($request->get('ageBetween')){
            $qb->andWhere('ageBetween.id = :id')
                ->setParameter('id', $request->get('ageBetween'));
        }

        if ($request->get('category')){
            $qb->andWhere('category.id = :id')
                ->setParameter('id', $request->get('category'));
        }

        if ($request->get('status')){
            $qb->andWhere('status.id = :id')
                ->setParameter('id', $request->get('status'));
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Blog[] Returns an array of Blog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Blog
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
