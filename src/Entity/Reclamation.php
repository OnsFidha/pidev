<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use GuzzleHttp\Client;

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
    // #[Assert\NotBlank(message:"Entrez votre réclamation s'il vous plait")]
    // #[Assert\Length(min:10, minMessage:"Votre reclamation ne contient pas {{ limit }} caractères.")]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $etat = false;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // private ?\DateTimeInterface $date_creation = null;
    private ?\DateTimeInterface $date_creation ;

    #[ORM\Column]
    private ?bool $generateWithAI = false;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $id_user = null;

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
// 
    public function setDescription(string $description): static
    {
//         $this->description = $description;
// 
//         return $this;
         // Vérifier si la description contient des mots interdits
        $descriptionCleaned = $this->filterBadWords($description);
    
        // Affecter la description nettoyée
        $this->description = $descriptionCleaned;

    return $this;

        

    // return $this;
    }






// 

// 


    private function filterBadWords(string $description): string
    {   
        // Read the list of bad words from the text file
        $badWords = file('C:\Users\HP\Desktop\symfony\esprit\p2\full-list-of-bad-words_text-file_2022_05_05.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Convertir la description en minuscules pour éviter les correspondances de casse
        $descriptionLowercase = strtolower($description);
        
        // Remplacer les mots interdits par des astérisques
        foreach ($badWords as $badWord) {
            $descriptionLowercase = str_ireplace($badWord, str_repeat('*', strlen($badWord)), $descriptionLowercase);
        }

        // Retourner la description nettoyée
        return $descriptionLowercase;
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

    public function isGenerateWithAI(): ?bool
    {
        return $this->generateWithAI;
    }

    public function setGenerateWithAI(bool $generateWithAI): static
    {
        $this->generateWithAI = $generateWithAI;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    
}
