<?php
namespace App\Controller;

use App\Entity\Ecurie;
use App\Entity\Pilote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UpdatePilotesEcurieController
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(Request $request, Ecurie $ecurie): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse(['error' => 'JSON invalide'], 400);
            }

            if (!isset($data['pilotes']) || !is_array($data['pilotes'])) {
                return new JsonResponse(['error' => 'Le champ "pilotes" est requis et doit être un tableau'], 400);
            }

            foreach ($data['pilotes'] as $pData) {
                if (empty($pData['nom']) || empty($pData['prenom'])) {
                    return new JsonResponse(['error' => 'Les champs "nom" et "prenom" sont requis pour chaque pilote'], 400);
                }

                
                $existing = $ecurie->getPilotes()->filter(function (Pilote $p) use ($pData) {
                    return $p->getNom() === $pData['nom'] && $p->getPrenom() === $pData['prenom'];
                })->first();

                if ($existing) {
                
                    if (isset($pData['points'])) {
                        $existing->setPoints((int)$pData['points']);
                    }
                    if (isset($pData['statut'])) {
                        $existing->setStatut($pData['statut']);
                    }
                    if (isset($pData['dateDebut'])) {
                        $existing->setDateDebut(new \DateTime($pData['dateDebut']));
                    }
                } else {

                    $pilote = new Pilote();
                    $pilote->setPrenom($pData['prenom']);
                    $pilote->setNom($pData['nom']);
                    $pilote->setPoints($pData['points'] ?? 12);
                    $pilote->setStatut($pData['statut'] ?? 'titulaire');
                    
                    
                    if (isset($pData['dateDebut'])) {
                        $pilote->setDateDebut(new \DateTime($pData['dateDebut']));
                    } else {
                        $pilote->setDateDebut(new \DateTime());
                    }
                    
                    
                    $ecurie->addPilote($pilote);
                }
            }

            $this->em->flush();

            return new JsonResponse([
                'message' => 'Pilotes mis à jour avec succès',
                'nombre_pilotes' => count($ecurie->getPilotes())
            ], 200);

        } catch (\Exception $e) {
           
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            
            return new JsonResponse([
                'error' => 'Une erreur est survenue lors de la mise à jour',
                'details' => $e->getMessage() 
            ], 500);
        }
    }
}
