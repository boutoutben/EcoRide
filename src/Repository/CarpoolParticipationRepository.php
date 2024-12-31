<?php

namespace App\Repository;

use App\Entity\CarpoolParticipation;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarpoolParticipation>
 */
class CarpoolParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarpoolParticipation::class);
    }

    public function countCreditPlatform(\DateTime $date, int $nbCredit): int
    {
        // Clone the date to avoid modifying the original object
        $startOfDay = (clone $date)->setTime(0, 0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        // Create the QueryBuilder instance
        $queryBuilder = $this->createQueryBuilder('c');

        // Build the query
        $queryBuilder
            ->select('COUNT(c.id)')
            ->where($queryBuilder->expr()->between('c.createAt', ':start', ':end'))
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay);

        // Execute the query and get the single scalar result
        $count = (int) $queryBuilder->getQuery()->getSingleScalarResult();

        // Multiply the count by the number of credits
        return $count * $nbCredit;
    }

    public function nbTotalCreditPlatform(int $nbCredit): int
    {
        $query = $this->createQueryBuilder("c")->select("COUNT(c.id)");
        $count = $query->getQuery()->getSingleScalarResult();
        return $count * $nbCredit;
    }

    //    /**
    //     * @return CarpoolParticipation[] Returns an array of CarpoolParticipation objects
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

    //    public function findOneBySomeField($value): ?CarpoolParticipation
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
