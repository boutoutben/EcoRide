<?php

namespace App\Repository;

use App\Entity\Carpool;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carpool>
 */
class CarpoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpool::class);
    }

    public function countDate(DateTime $data)
    {
        $formattedDate = $data->format('Y-m-d'); // Extract only the date part

        $query = $this->createQueryBuilder("c")
        ->select("COUNT(c.id)")
        ->where("c.startDate BETWEEN :start AND :end") // Compare within the range of the day
        ->setParameter("start", $formattedDate . ' 00:00:00')
        ->setParameter("end", $formattedDate . ' 23:59:59');

        return (int) $query->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Carpool[] Returns an array of Carpool objects
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

//    public function findOneBySomeField($value): ?Carpool
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
