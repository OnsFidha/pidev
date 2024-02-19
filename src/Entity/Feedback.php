<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Entrez votre avis")]
    #[Assert\Length(max: 255, maxMessage: "Le feedback ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'feedback')]
    private ?Evenement $id_evenement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getIdEvenement(): ?Evenement
    {
        return $this->id_evenement;
    }

    public function setIdEvenement(?Evenement $id_evenement): static
    {
        $this->id_evenement = $id_evenement;

        return $this;
    }
}
