<?php

namespace App\Repository;

use App\Entity\EventManagement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventManagement|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventManagement|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventManagement[]    findAll()
 * @method EventManagement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventManagementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventManagement::class);
    }

    // /**
    //  * @return EventManagement[] Returns an array of EventManagement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventManagement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
