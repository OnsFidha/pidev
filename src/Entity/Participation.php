<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participations')]
    private Collection $IdUser;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'participations')]
    private Collection $Idevent;

    public function __construct()
    {
        $this->IdUser = new ArrayCollection();
        $this->Idevent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, user>
     */
    public function getIdUser(): Collection
    {
        return $this->IdUser;
    }

    public function addIdUser(user $idUser): static
    {
        if (!$this->IdUser->contains($idUser)) {
            $this->IdUser->add($idUser);
        }

        return $this;
    }

    public function removeIdUser(user $idUser): static
    {
        $this->IdUser->removeElement($idUser);

        return $this;
    }

    /**
     * @return Collection<int, evenement>
     */
    public function getIdevent(): Collection
    {
        return $this->Idevent;
    }

    public function addIdevent(evenement $idevent): static
    {
        if (!$this->Idevent->contains($idevent)) {
            $this->Idevent->add($idevent);
        }

        return $this;
    }

    public function removeIdevent(evenement $idevent): static
    {
        $this->Idevent->removeElement($idevent);

        return $this;
    }
    public function __toString(): string
    {
        return $this->getIdevent();
        return $this->getIdUser(); // Assuming getUsername() returns a string property of the User entity
    }
}
