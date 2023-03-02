<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


      /**
    * @return Product[] Returns an array of Cart objects
    */
    public function showAll(): array
    {
        return $this->createQueryBuilder('c')
         ->select('p.id, p.price, p.name, p.created, p.quantity, p.image, p.description, c.id cat, s.id sup')
            ->innerJoin('p.cat_id','c')
            ->innerJoin('p.sup_id','s')
            ->getQuery()
            ->getArrayResult()
        ;
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

     /**
    * @return Product[] Returns an array of Product objects
    */
   public function findPro($value): array
   {
       return $this->createQueryBuilder('p')
           ->select('p.id,p.name,p.price,p.image,p.description')
           ->where('p.name LIKE :productName')
           ->setParameter('productName',"%".$value."%")
           ->getQuery()
           ->getResult()
       ;
   }
}
