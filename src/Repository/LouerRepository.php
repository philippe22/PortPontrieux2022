<?php

namespace App\Repository;

use App\Entity\Louer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Louer>
 *
 * @method Louer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Louer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Louer[]    findAll()
 * @method Louer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LouerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Louer::class);
    }

    public function add(Louer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Louer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Louer[] Returns an array of Louer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Louer
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function listeLocation($cote)
    {
        //var_dump($cote);
        $qb = $this->createQueryBuilder('l');
        $qb->join('l.emplacement', 'e'); // Jointure avec Emplacement. Inutile de préciser avec quelle entité car c'est déja noté dans les annotatations de l'entité Louer
        $qb->join('e.type', 't'); // Jointure avec Type. Inutile de préciser avec quelle entité car c'est déja noté dans les annotatations de l'entité Emplacement
        $qb->where('t.situation = :cote'); // Critère de sélection sur la situation (rue ou rive)
        $qb->setParameter('cote', $cote);
        $query = $qb->getQuery();
        
        // On récupère les résultats à partir de la Query
        $results = $query->getResult();

        // On retourne ces résultats
        return $results;
    }
}
