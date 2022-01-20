<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MusicgameTrackRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MusicgameTrackRepository::class)
 */
class MusicgameTrack
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"trackOnly"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Groups({"trackOnly"})
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"trackOnly"})
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"trackOnly"})
     */
    private $isOnline;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"trackOnly"})
     */
    private $fullname;

    /**
     * @ORM\ManyToOne(targetEntity=Musicgame::class, inversedBy="tracks")
     * @ORM\JoinColumn(name="musicgame_id", referencedColumnName="id", nullable=false)
     */
    private $musicgame;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getMusicgame(): ?Musicgame
    {
        return $this->musicgame;
    }

    public function setMusicgame(?Musicgame $musicgame): self
    {
        $this->musicgame = $musicgame;

        return $this;
    }
}
