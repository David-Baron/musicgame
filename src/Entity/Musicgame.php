<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use App\Repository\MusicgameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MusicgameRepository::class)
 * @UniqueEntity( fields={"name", "slug"}, message="This musicgame already exist" )
 */
class Musicgame
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isActive = 0;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=MusicgamePlaylist::class, mappedBy="musicgame", orphanRemoval=true)
     */
    private $playlists;

    /**
     * @ORM\OneToMany(targetEntity=MusicgameTrack::class, mappedBy="musicgame", orphanRemoval=true, cascade={"persist"})
     */
    private $tracks;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->tracks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|MusicgamePlaylist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(MusicgamePlaylist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->setMusicgame($this);
        }

        return $this;
    }

    public function removePlaylist(MusicgamePlaylist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getMusicgame() === $this) {
                $playlist->setMusicgame(null);
            }
        }

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
            $track->setMusicgame($this);
        }

        return $this;
    }

    public function removeTrack(MusicgameTrack $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getMusicgame() === $this) {
                $track->setMusicgame(null);
            }
        }

        return $this;
    }

}
