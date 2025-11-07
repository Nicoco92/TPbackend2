<?php

namespace App\DataFixtures;

use App\Entity\Pilote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PiloteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $pilotesData = [
            // Red Bull
            ['ecurie_ref' => 0, 'prenom' => 'Max', 'nom' => 'Verstappen', 'statut' => 'titulaire', 'points' => 12, 'date' => '2015-03-15'],
            ['ecurie_ref' => 0, 'prenom' => 'Sergio', 'nom' => 'Perez', 'statut' => 'titulaire', 'points' => 12, 'date' => '2011-03-13'],
            ['ecurie_ref' => 0, 'prenom' => 'Liam', 'nom' => 'Lawson', 'statut' => 'reserviste', 'points' => 12, 'date' => '2023-09-03'],

            // Ferrari
            ['ecurie_ref' => 1, 'prenom' => 'Charles', 'nom' => 'Leclerc', 'statut' => 'titulaire', 'points' => 12, 'date' => '2018-03-25'],
            ['ecurie_ref' => 1, 'prenom' => 'Carlos', 'nom' => 'Sainz', 'statut' => 'titulaire', 'points' => 12, 'date' => '2015-03-15'],
            ['ecurie_ref' => 1, 'prenom' => 'Oliver', 'nom' => 'Bearman', 'statut' => 'reserviste', 'points' => 12, 'date' => '2024-03-10'],

            // Mercedes
            ['ecurie_ref' => 2, 'prenom' => 'Lewis', 'nom' => 'Hamilton', 'statut' => 'titulaire', 'points' => 12, 'date' => '2007-03-18'],
            ['ecurie_ref' => 2, 'prenom' => 'George', 'nom' => 'Russell', 'statut' => 'titulaire', 'points' => 12, 'date' => '2019-03-17'],
            ['ecurie_ref' => 2, 'prenom' => 'Mick', 'nom' => 'Schumacher', 'statut' => 'reserviste', 'points' => 12, 'date' => '2021-03-28'],
        ];

        foreach ($pilotesData as $index => $p) {
            $pilote = new Pilote();
            $pilote->setPrenom($p['prenom']);
            $pilote->setNom($p['nom']);
            $pilote->setPoints($p['points']);
            $pilote->setStatut($p['statut']);
            $pilote->setDateDebut(new \DateTime($p['date']));
            $pilote->setEcurie($this->getReference('ecurie_' . $p['ecurie_ref']));

            $manager->persist($pilote);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EcurieFixtures::class,
        ];
    }
}