<?php

namespace App\Repository;

use App\Entity\ProductCartOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductCartOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCartOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCartOption[]    findAll()
 * @method ProductCartOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCartOptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductCartOption::class);
    }

    // /**
    //  * @return ProductCartOption[] Returns an array of ProductCartOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductCartOption
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
