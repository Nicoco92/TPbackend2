<?php

namespace App\DataFixtures;

use App\Entity\Ecurie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EcurieFixtures extends Fixture implements DependentFixtureInterface
{
    public const ECURIES = [
        ['nom' => 'Red Bull Racing', 'moteur_ref' => 0],
        ['nom' => 'Scuderia Ferrari', 'moteur_ref' => 1],
        ['nom' => 'Mercedes AMG F1', 'moteur_ref' => 2],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ECURIES as $index => $data) {
            $ecurie = new Ecurie();
            $ecurie->setNom($data['nom']);
            $ecurie->setMoteur($this->getReference('moteur_' . $data['moteur_ref']));

            $manager->persist($ecurie);
            $this->addReference('ecurie_' . $index, $ecurie);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MoteurFixtures::class,
        ];
    }
}