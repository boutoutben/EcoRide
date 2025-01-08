<?php

namespace App\Repository;

use App\Entity\Carpool;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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

    public function searchCarpool($data,$filter)
    {
        $date = new DateTime();
        if(key_exists("startDate",$data))
        {
            $date = $data["startDate"];
        }
        $query = $this->createQueryBuilder('c')
            ->select('c', 'u') // Select fields from both Carpool (c) and User (u)
            ->join('c.user', 'u') // Adjust this to your actual relationship
            ->where('c.startPlace = :start_place')
            ->andWhere('c.endPlace = :end_place')
            ->andWhere("c.startDate >= :startDate")
            ->andWhere("c.isFinish = false")
            ->andWhere("c.placeLeft > 0")
            ->setParameters(new ArrayCollection([
                new Parameter('start_place', $data["startPlace"]),
                new Parameter('end_place', $data["endPlace"]),
                new Parameter("startDate",$date)
            ]));
        if(isset($filter["isEcologique"])&& $filter["isEcologique"]!= [])
        {
            $query->andWhere("c.isEcologique = true");
        }
        if (isset($filter["maxPrice"]))
        {
            $query->andWhere("c.price <= {$filter['maxPrice']}");
        }
        if(isset($filter["maxTime"]))
        {
            $hour = $filter["maxTime"]->format('H');
            $minute = $filter["maxTime"]->format('i');

            $query->andWhere('c.endDate <= DATE_ADD(DATE_ADD(c.startDate, :hour, \'HOUR\'), :minute, \'MINUTE\')')
            ->setParameter("hour", $hour)
            ->setParameter("minute", $minute);

            
        }
        if(isset($filter["minMark"]))
        {
            $query->andWhere("u.mark >= {$filter['minMark']}");
        }
        return $query->getQuery()->getResult();
        
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
