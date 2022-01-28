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

}
