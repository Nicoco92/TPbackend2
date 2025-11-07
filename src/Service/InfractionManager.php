<?php

namespace App\Service;

use App\Entity\Infraction;
use App\Entity\Pilote;
use Doctrine\ORM\EntityManagerInterface;

class InfractionManager
{
    public function __construct(private EntityManagerInterface $em) {}

    public function process(Infraction $infraction): void
    {
        if ($infraction->getType() === 'penalite' && $infraction->getPilote()) {
            $pilote = $infraction->getPilote();
            $pointsRestants = $pilote->getPoints() - $infraction->getValeur();
            $pilote->setPoints($pointsRestants);

            if ($pointsRestants < 12) {
                $pilote->setStatut('suspendu');
            }

            $this->em->persist($pilote);
        }
    }
}