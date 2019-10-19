<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

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

    /**
     * @param int $id
     * @return mixed
     */
    public function getLibBooksToPagination(int $id)
    {
        return $this->createQueryBuilder('l')
            ->addSelect('b')
            ->innerJoin('l.books', 'b')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return array
     * @throws DBALException
     */
    public function getBooksToAdd(int $id): array
    {
        $dc = $this->getEntityManager()->getConnection();
        $sql = "SELECT id FROM book WHERE book.id NOT IN (SELECT book_id FROM libra_books 
            WHERE libra_id = $id)";
        $ids = [];
        $stmt = $dc->executeQuery($sql);
        while($res = $stmt->fetch()) {
            $ids[] = $res['id'];
        }
        $booksToAdd = $this->getEntityManager()->getRepository(Book::class)
            ->findBy(['id' => $ids]);

        return $booksToAdd;
    }
}
