<?php

namespace App\Repository;

use App\Entity\LiteraryType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method LiteraryType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiteraryType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiteraryType[]    findAll()
 * @method LiteraryType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiteraryTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiteraryType::class);
    }

    /**
     * @param string $bookType
     * @return array
     * @throws DBALException
     */
    public function getTypesToBookEdit(string $bookType): array
    {
        $dc = $this->getEntityManager()->getConnection();
        $sql = "SELECT type FROM literary_type WHERE type <> '$bookType'";
        $types = [];
        $stmt = $dc->executeQuery($sql);
        while ($row = $stmt->fetch()) {
            $types[] = $row['type'];
        }

        return $types;
    }
}
