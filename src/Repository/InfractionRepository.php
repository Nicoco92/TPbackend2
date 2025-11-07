<?php

namespace App\Repository;

use App\Entity\Infraction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InfractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infraction::class);
    }

    /**
     * Retourne les infractions filtrées.
     *
     * @return Infraction[]
     */
    public function findWithFilters(
        ?int $ecurieId,
        ?int $piloteId,
        ?\DateTimeInterface $dateFrom,
        ?\DateTimeInterface $dateTo
    ): array {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.ecurie', 'e')->addSelect('e')
            ->leftJoin('i.pilote', 'p')->addSelect('p')
            ->orderBy('i.date', 'DESC');

        if ($ecurieId) {
            $qb->andWhere('e.id = :ecurieId')
               ->setParameter('ecurieId', $ecurieId);
        }

        if ($piloteId) {
            $qb->andWhere('p.id = :piloteId')
               ->setParameter('piloteId', $piloteId);
        }

        if ($dateFrom) {
            $qb->andWhere('i.date >= :dateFrom')
               ->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo) {
            $qb->andWhere('i.date <= :dateTo')
               ->setParameter('dateTo', $dateTo);
        }

        return $qb->getQuery()->getResult();
    }
}
