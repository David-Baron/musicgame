<?php

namespace App\Repository;

use App\Entity\MusicgameTrack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicgameTrack|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicgameTrack|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicgameTrack[]    findAll()
 * @method MusicgameTrack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicgameTrackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicgameTrack::class);
    }

    // /**
    //  * @return MusicgameTrack[] Returns an array of MusicgameTrack objects
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
    public function findOneBySomeField($value): ?MusicgameTrack
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
