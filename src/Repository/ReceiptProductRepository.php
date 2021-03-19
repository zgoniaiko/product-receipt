<?php

namespace App\Repository;

use App\Entity\ReceiptProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReceiptProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceiptProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceiptProduct[]    findAll()
 * @method ReceiptProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReceiptProduct::class);
    }

    // /**
    //  * @return ReceiptProduct[] Returns an array of ReceiptProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReceiptProduct
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
