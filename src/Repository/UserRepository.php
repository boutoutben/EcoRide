<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User2>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private OpinionRepository $opinionRepository;
    public function __construct(ManagerRegistry $registry, OpinionRepository $opinionRepository)
    {
        parent::__construct($registry, User::class);
        $this->opinionRepository = $opinionRepository;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getUserAndEmployee()
    {
        $query = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMINISTRATEUR%');

        return $query->getQuery()->getArrayResult();
    }

    /*public function updateUserMark(User $user)
    {
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $this->opinionRepository->getAVGMark($user);
        $em->persist($this);

    }*/

    //    /**
    //     * @return User2[] Returns an array of User2 objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User2
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
