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

    public function getLibBooksToPagination(int $id)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('b')
            ->innerJoin('l.books', 'b')
            ->where('l.id = ' . $id)
            ->getQuery()
            ->getResult();
    }

    public function getBookListToAdd(int $id)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('b')
            ->innerJoin('l.books', 'b')
            ->where('l.id != ' . $id)
            ->getQuery()
            ->getResult();
    }
}
