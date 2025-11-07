<?php

namespace App\Entity;

use App\Repository\InfractionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfractionRepository::class)]
class Infraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCourse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $penalitePoints = null;

    #[ORM\Column(nullable: true)]
    private ?float $amendeEuros = null;

    #[ORM\ManyToOne(targetEntity: Pilote::class)]
    private ?Pilote $pilote = null;

    #[ORM\ManyToOne(targetEntity: Ecurie::class)]
    private ?Ecurie $ecurie = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCourse(): ?string
    {
        return $this->nomCourse;
    }

    public function setNomCourse(string $nomCourse): static
    {
        $this->nomCourse = $nomCourse;

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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPenalitePoints(): ?int
    {
        return $this->penalitePoints;
    }

    public function setPenalitePoints(?int $penalitePoints): static
    {
        $this->penalitePoints = $penalitePoints;

        return $this;
    }

    public function getAmendeEuros(): ?float
    {
        return $this->amendeEuros;
    }

    public function setAmendeEuros(?float $amendeEuros): static
    {
        $this->amendeEuros = $amendeEuros;

        return $this;
    }

    public function getPilote(): ?Pilote
    {
        return $this->pilote;
    }

    public function setPilote(?Pilote $pilote): static
    {
        $this->pilote = $pilote;

        return $this;
    }

    public function getEcurie(): ?Ecurie
    {
        return $this->ecurie;
    }

    public function setEcurie(?Ecurie $ecurie): static
    {
        $this->ecurie = $ecurie;

        return $this;
    }
}