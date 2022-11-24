<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $intitule;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $details;

    /**
     * @ORM\Column(type="date")
     */
    private $dateAffichage;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDesaffichage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDateAffichage(): ?\DateTimeInterface
    {
        return $this->dateAffichage;
    }

    public function setDateAffichage(\DateTimeInterface $dateAffichage): self
    {
        $this->dateAffichage = $dateAffichage;

        return $this;
    }

    public function getDateDesaffichage(): ?\DateTimeInterface
    {
        return $this->dateDesaffichage;
    }

    public function setDateDesaffichage(\DateTimeInterface $dateDesaffichage): self
    {
        $this->dateDesaffichage = $dateDesaffichage;

        return $this;
    }
}
