<?php

namespace App\Repository;

use App\Entity\DetalleSiniestro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetalleSiniestro>
 */
class DetalleSiniestroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetalleSiniestro::class);
    }

//    /**
//     * @return DetalleSiniestro[] Returns an array of DetalleSiniestro objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetalleSiniestro
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
        public function reporteRolesPorSiniestros(array $filtros): array
        {
            $conn = $this->getEntityManager()->getConnection();

            $sql = "SELECT r.descripcion AS rol, COUNT(ds.id) AS cantidad
                    FROM detalle_siniestro ds
                    INNER JOIN siniestro s ON s.id = ds.id_siniestro_id
                    INNER JOIN rol r ON r.id = ds.rol_id
                    WHERE 1=1";

            $params = [];

            if (!empty($filtros['fechaDesde'])) {
                $sql .= " AND s.fecha >= :desde";
                $params['desde'] = $filtros['fechaDesde']->format('Y-m-d');
            }

            if (!empty($filtros['fechaHasta'])) {
                $sql .= " AND s.fecha <= :hasta";
                $params['hasta'] = $filtros['fechaHasta']->format('Y-m-d');
            }

            $sql .= " GROUP BY r.descripcion ORDER BY cantidad DESC ";

            $stmt = $conn->prepare($sql);
            $result = $stmt->executeQuery($params);

            return $result->fetchAllAssociative();
        }

}
