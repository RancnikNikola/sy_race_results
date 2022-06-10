<?php

namespace App\Repository;

use App\Entity\CsvUploadFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CsvUploadFile>
 *
 * @method CsvUploadFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvUploadFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvUploadFile[]    findAll()
 * @method CsvUploadFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvUploadFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvUploadFile::class);
    }

    public function add(CsvUploadFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CsvUploadFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CsvUploadFile[] Returns an array of CsvUploadFile objects
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

//    public function findOneBySomeField($value): ?CsvUploadFile
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
