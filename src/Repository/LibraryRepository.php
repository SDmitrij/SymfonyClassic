<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
     * @return array
     */
    public function getLibListToDropDown(): array
    {
        return $this->createQueryBuilder('l')
            ->select('l.id, l.address')
            ->getQuery()
            ->getArrayResult();
    }

    public function getLibBooksToPagination($id): array
    {
        return $this->createQueryBuilder('l')
            ->addSelect('b', 'a')
            ->innerJoin('l.books', 'b')
            ->innerJoin('l.authors', 'a')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->orderBy('l.created', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    // /**
    //  * @return Library[] Returns an array of Library objects
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
    public function findOneBySomeField($value): ?Library
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
