<?php

namespace App\Entity;

use App\Repository\LouerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LouerRepository::class)
 */
class Louer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Emplacement", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $emplacement;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomBateau;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $portAttache;

    /**
     * @ORM\Column(type="date")
     */
    private $dateArrivee;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reglement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomBateau(): ?string
    {
        return $this->nomBateau;
    }

    public function setNomBateau(string $nomBateau): self
    {
        $this->nomBateau = $nomBateau;

        return $this;
    }

    public function getPortAttache(): ?string
    {
        return $this->portAttache;
    }

    public function setPortAttache(string $portAttache): self
    {
        $this->portAttache = $portAttache;
        return $this;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): self
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function isReglement(): ?bool
    {
        return $this->reglement;
    }

    public function setReglement(bool $reglement): self
    {
        $this->reglement = $reglement;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }
}
