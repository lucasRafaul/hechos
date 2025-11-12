<?php

namespace App\Repository;

use App\Entity\Siniestro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
#use DoctrineExtensions\Query\Mysql;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Siniestro>
 */
class SiniestroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Siniestro::class);
    }

//    /**
//     * @return Siniestro[] Returns an array of Siniestro objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Siniestro
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
        public function reporteSiniestrosPorMes(array $filtros): array
        {
            $qb = $this->createQueryBuilder('s')
            ->select("CONCAT(YEAR(s.fecha), '-', MONTH(s.fecha)) AS periodo, COUNT(s.id) AS cantidad")
            ->groupBy('periodo')
            ->orderBy('periodo', 'ASC');

            if (!empty($filtros['fechaDesde'])) {
                $qb->andWhere('s.fecha >= :desde')
                ->setParameter('desde', $filtros['fechaDesde']);
            }

            if (!empty($filtros['fechaHasta'])) {
                $qb->andWhere('s.fecha <= :hasta')
                ->setParameter('hasta', $filtros['fechaHasta']);
            }

            return $qb->getQuery()->getResult();
        }

        public function reportePorLocalidad(array $filtros): array
        {
            $qb = $this->createQueryBuilder('s')
                ->select('l.nombre AS localidad, COUNT(s.id) AS cantidad')
                ->join('s.localidad', 'l')
                ->groupBy('l.nombre')
                ->orderBy('cantidad', 'DESC');

            if (!empty($filtros['fechaDesde'])) {
                $qb->andWhere('s.fecha >= :desde')
                ->setParameter('desde', $filtros['fechaDesde']);
            }

            if (!empty($filtros['fechaHasta'])) {
                $qb->andWhere('s.fecha <= :hasta')
                ->setParameter('hasta', $filtros['fechaHasta']);
            }

            return $qb->getQuery()->getResult();
        }



        }


        
