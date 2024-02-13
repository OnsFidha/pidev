<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Entrez un type s'il vous plait")]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Entrez votre réclamation s'il vous plait")]
    #[Assert\Length(min:10, minMessage:"Votre reclamation ne contient pas {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $etat = false;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // private ?\DateTimeInterface $date_creation = null;
    private ?\DateTimeInterface $date_creation ;

    public function __construct()
    {
        $this->date_creation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        // $this->date_creation = $date_creation;
        if ($this->date_creation === null) {
            $this->date_creation = new \DateTime();
        }

        return $this;
    }

    public function __toString(): string
{
    return $this->getId() !== null ? (string) $this->getId() : '';
}
}
