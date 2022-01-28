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
}
