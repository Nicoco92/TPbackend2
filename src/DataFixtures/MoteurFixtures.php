<?php

namespace App\DataFixtures;

use App\Entity\Moteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MoteurFixtures extends Fixture
{
    public const MOTEURS = [
        'Honda',
        'Ferrari',
        'Mercedes',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MOTEURS as $index => $marque) {
            $moteur = new Moteur();
            $moteur->setMarque($marque);
            $manager->persist($moteur);

            // On garde une référence pour l’utiliser dans EcurieFixtures
            $this->addReference('moteur_' . $index, $moteur);
        }

        $manager->flush();
    }
}