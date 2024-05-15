<?php

/**
 * PHP version 8.2.12
 *
 * @category                 Repository
 * @package                  App\Repository
 * @Entity
 * @Table(name="tournament")
 * @author                   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                  https://opensource.org/licenses/MIT MIT License
 * @link                     src/Repository/TournamentRepository.php
 */

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tournament>
 *
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    //    /**
    //     * @return Tournament[] Returns an array of Tournament objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tournament
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countWins($userId, $tournamentId = null): int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.winner = :user')
            ->setParameter('user', $userId);

        if ($tournamentId) {
            $qb->andWhere('t.id = :tournamentId')
                ->setParameter('tournamentId', $tournamentId);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countLosses($userId, $tournamentId = null): int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.organizer = :user')
            ->andWhere('t.winner != :user')
            ->setParameter('user', $userId);

        if ($tournamentId) {
            $qb->andWhere('t.id = :tournamentId')
                ->setParameter('tournamentId', $tournamentId);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
