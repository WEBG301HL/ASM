<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function add(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Cart[] Returns an array of Cart objects
    */
   public function cartShow($value): array
   {
       return $this->createQueryBuilder('c')
        ->select('c.id cid, p.name , c.quantity, p.price, p.image')
            ->innerJoin('c.proCart','p')
            ->innerJoin('c.userCart','u')
           ->where('u.id = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getArrayResult()
       ;
   }

    /**
    * @return Cart[] Returns an array of Cart objects
    */
    public function totalPricecart($value): array
    {
        return $this->createQueryBuilder('c')
         ->select('SUM( c.quantity * p.price) Total')
             ->innerJoin('c.proCart','p')
             ->innerJoin('c.userCart','u')
            ->where('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getArrayResult()
        ;
    }

      /**
    * @return Cart[] Returns an array of Cart objects
    */
    public function cartOrderBy($value, $sort): array
    {
        return $this->createQueryBuilder('c')
         ->select('c.id cid, p.name , c.quantity, p.price, p.image')
             ->innerJoin('c.proCart','p')
             ->innerJoin('c.userCart','u')
            ->where('u.id = :val')
            ->setParameter('val', $value)
            ->addOrderBy('p.price', "$sort")
            ->getQuery()
            ->getArrayResult()
        ;
    }


//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
