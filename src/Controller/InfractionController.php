<?php

namespace App\Controller;

use App\Entity\Infraction;
use App\Entity\Pilote;
use App\Entity\Ecurie;
use App\Service\InfractionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InfractionController
{
    #[Route('/api/infraction', name: 'add_infraction', methods: ['POST'])]
    public function addInfraction(
        Request $request,
        EntityManagerInterface $em,
        InfractionManager $infractionManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Vérification des champs obligatoires
        if (!isset($data['type'], $data['valeur'], $data['date'], $data['nomCourse'], $data['description'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], 400);
        }

        $infraction = new Infraction();
        $infraction->setNomCourse($data['nomCourse']);
        $infraction->setDescription($data['description']);
        $infraction->setDate(new \DateTime($data['date']));

        // Affectation selon le type
        if ($data['type'] === 'penalite') {
            $infraction->setPenalitePoints((int) $data['valeur']);
        } elseif ($data['type'] === 'amende') {
            $infraction->setAmendeEuros((float) $data['valeur']);
        } else {
            return new JsonResponse(['error' => 'Type d\'infraction invalide'], 400);
        }

        // Association pilote
        if (isset($data['piloteId'])) {
            $pilote = $em->getRepository(Pilote::class)->find($data['piloteId']);
            if (!$pilote) {
                return new JsonResponse(['error' => 'Pilote non trouvé'], 404);
            }
            $infraction->setPilote($pilote);
        }

        // Association écurie
        if (isset($data['ecurieId'])) {
            $ecurie = $em->getRepository(Ecurie::class)->find($data['ecurieId']);
            if (!$ecurie) {
                return new JsonResponse(['error' => 'Écurie non trouvée'], 404);
            }
            $infraction->setEcurie($ecurie);
        }

        // Traitement métier (suspension automatique)
        $infractionManager->process($infraction);

        $em->persist($infraction);
        $em->flush();

        return new JsonResponse(['message' => 'Infraction enregistrée'], 201);
    }
}
