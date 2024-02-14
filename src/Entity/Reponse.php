<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_reponse ;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reclamation $relation = null;


    public function __construct()
    {
        $this->date_reponse = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(\DateTimeInterface $date_reponse): static
    {
        // $this->date_reponse = $date_reponse;
         if ($this->date_reponse === null) {
            $this->date_reponse = new \DateTime();
        }

        return $this;

        return $this;
    }

    public function getRelation(): ?Reclamation
    {
        return $this->relation;
    }

    public function setRelation(?Reclamation $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

   

}
