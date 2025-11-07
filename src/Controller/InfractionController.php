<?php

namespace App\Controller;

use App\Repository\InfractionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Infraction;
use App\Entity\Ecurie;
use App\Entity\Pilote;
use Doctrine\ORM\EntityManagerInterface;

class InfractionController extends AbstractController
{
    #[Route('/api/infractions', name: 'api_infractions_list', methods: ['GET'])]
    public function listInfractions(Request $request, InfractionRepository $repo): JsonResponse
    {
        $ecurieId = $request->query->get('ecurie');
        $piloteId = $request->query->get('pilote');
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');

        $dateFromObj = $dateFrom ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateFrom) : null;
        $dateToObj = $dateTo ? \DateTimeImmutable::createFromFormat('Y-m-d', $dateTo) : null;

        if (($dateFrom && !$dateFromObj) || ($dateTo && !$dateToObj)) {
            return $this->json(['error' => 'Format de date invalide (attendu : YYYY-MM-DD)'], 400);
        }

        $infractions = $repo->findWithFilters(
            $ecurieId ? (int)$ecurieId : null,
            $piloteId ? (int)$piloteId : null,
            $dateFromObj,
            $dateToObj
        );

        $data = array_map(function ($infraction) {
            return [
                'id' => $infraction->getId(),
                'nomCourse' => $infraction->getNomCourse(),
                'description' => $infraction->getDescription(),
                'date' => $infraction->getDate()->format('Y-m-d H:i'),
                'penalitePoints' => $infraction->getPenalitePoints(),
                'amendeEuros' => $infraction->getAmendeEuros(),
                'pilote' => $infraction->getPilote() ? [
                    'id' => $infraction->getPilote()->getId(),
                    'prenom' => $infraction->getPilote()->getPrenom(),
                    'nom' => $infraction->getPilote()->getNom(),
                ] : null,
                'ecurie' => $infraction->getEcurie() ? [
                    'id' => $infraction->getEcurie()->getId(),
                    'nom' => $infraction->getEcurie()->getNom(),
                ] : null,
            ];
        }, $infractions);

        return $this->json($data);
    }
    #[Route('/api/infractions', name: 'api_infractions_create', methods: ['POST'])]
public function createInfraction(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json(['error' => 'Requête invalide, JSON manquant'], 400);
    }

    $infraction = new Infraction();
    $infraction->setNomCourse($data['nomCourse'] ?? 'Course inconnue');
    $infraction->setDescription($data['description'] ?? '');
    $infraction->setPenalitePoints($data['penalitePoints'] ?? null);
    $infraction->setAmendeEuros($data['amendeEuros'] ?? null);

    if (isset($data['date'])) {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $data['date']);
        if ($date) {
            $infraction->setDate($date);
        }
    }

    if (isset($data['pilote_id'])) {
        $pilote = $em->getRepository(Pilote::class)->find($data['pilote_id']);
        if ($pilote) $infraction->setPilote($pilote);
    }

    if (isset($data['ecurie_id'])) {
        $ecurie = $em->getRepository(Ecurie::class)->find($data['ecurie_id']);
        if ($ecurie) $infraction->setEcurie($ecurie);
    }

    $em->persist($infraction);
    $em->flush();

    return $this->json([
        'message' => 'Infraction créée avec succès',
        'id' => $infraction->getId()
    ], 201);
}
}
