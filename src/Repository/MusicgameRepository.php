<?php

namespace App\Repository;

use App\Entity\Musicgame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Musicgame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musicgame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musicgame[]    findAll()
 * @method Musicgame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicgameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Musicgame::class);
    }

    // /**
    //  * @return Musicgame[] Returns an array of Musicgame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Musicgame
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
