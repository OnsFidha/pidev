<?php

namespace App\Entity;

use App\Repository\WhatsappNotifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WhatsappNotifRepository::class)]
class WhatsappNotif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Reclamation $id_reclam = null;

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
        // $this->text = "user name : a ajouter une reclamation ";

        return $this;
    }

    public function getIdReclam(): ?Reclamation
    {
        return $this->id_reclam;
    }

    public function setIdReclam(?Reclamation $id_reclam): static
    {
        $this->id_reclam = $id_reclam;

        return $this;
    }
}
