<?php

namespace App\Entity;

use App\Repository\CollaborationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollaborationRepository::class)]
class Collaboration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $disponibilite = null;

    #[ORM\Column(length: 255)]
    private ?string $competence = null;

    #[ORM\Column(length: 255)]
    private ?string $cv = null;

    #[ORM\ManyToMany(targetEntity: Publication::class, inversedBy: 'collaborations')]
    private Collection $pub;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'collaborations')]
    private Collection $user;

    public function __construct()
    {
        $this->pub = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getCompetence(): ?string
    {
        return $this->competence;
    }

    public function setCompetence(string $competence): static
    {
        $this->competence = $competence;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPub(): Collection
    {
        return $this->pub;
    }

    public function addPub(Publication $pub): static
    {
        if (!$this->pub->contains($pub)) {
            $this->pub->add($pub);
        }

        return $this;
    }

    public function removePub(Publication $pub): static
    {
        $this->pub->removeElement($pub);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }
}
