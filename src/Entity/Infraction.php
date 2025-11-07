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

    // getters & setters à générer (mêmes principes que les autres entités)
}