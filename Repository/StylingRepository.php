<?php

namespace Sulu\Bundle\StylingBundle\Repository;

use Sulu\Bundle\StylingBundle\Entity\Styling;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Styling|null find($id, $lockMode = null, $lockVersion = null)
 * @method Styling|null findOneBy(array $criteria, array $orderBy = null)
 * @method Styling[]    findAll()
 * @method Styling[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StylingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Styling::class);
    }

    // /**
    //  * @return Styling[] Returns an array of Styling objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Styling
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
