<?php

namespace App\Repository;

use App\Entity\MusicgamePlaylist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicgamePlaylist|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicgamePlaylist|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicgamePlaylist[]    findAll()
 * @method MusicgamePlaylist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicgamePlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicgamePlaylist::class);
    }

    // /**
    //  * @return MusicgamePlaylist[] Returns an array of MusicgamePlaylist objects
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
    public function findOneBySomeField($value): ?MusicgamePlaylist
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
