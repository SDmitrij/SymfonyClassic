<?php

namespace App\Repository;

use App\Entity\LibraLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LibraLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LibraLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LibraLocation[]    findAll()
 * @method LibraLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LibraLocation::class);
    }

    // /**
    //  * @return LibraLocation[] Returns an array of LibraLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LibraLocation
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
