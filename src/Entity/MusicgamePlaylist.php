<?php

namespace App\Entity;

use App\Repository\MusicgamePlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MusicgamePlaylistRepository::class)
 */
class MusicgamePlaylist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50 , unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=MusicgameTrack::class)
     */
    private $tracks;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isAutodelete = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Musicgame::class, inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $musicgame;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|MusicgameTrack[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(MusicgameTrack $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
        }

        return $this;
    }

    public function removeTrack(MusicgameTrack $track): self
    {
        $this->tracks->removeElement($track);

        return $this;
    }

    public function getIsAutodelete(): ?bool
    {
        return $this->isAutodelete;
    }

    public function setIsAutodelete(bool $isAutodelete): self
    {
        $this->isAutodelete = $isAutodelete;

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
