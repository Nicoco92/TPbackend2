<?php


#[ORM\Entity]
class Infraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;
    #[ORM\Column]
    private ?float $valeur = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCourse = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Pilote::class)]
    private ?Pilote $pilote = null;

    #[ORM\ManyToOne(targetEntity: Ecurie::class)]
    private ?Ecurie $ecurie = null;
}