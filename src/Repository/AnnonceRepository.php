<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function add(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	// Fonction qui liste l'ensemble des annonces
	public function listeDesAnnoncesAffichables()
	{
		$dateDuJour = date('Y-m-d');
		$qb = $this->createQueryBuilder('a');
		$qb->andWhere(':dateJour >= a.dateAffichage');
		$qb->andWhere(':dateJour <= a.dateDesaffichage');
		$qb->setParameter('dateJour', $dateDuJour);
		$res = $qb->getQuery()->getResult();
		return $res;
    }
    
    // Fonction qui détaille une annonce
	public function detailsAnnonce($id)
	{
        $qb = $this->createQueryBuilder('a');
        $qb->andWhere('a.id = :id');
		$qb->setParameter('id', $id);
        $res = $qb->getQuery()->getOneOrNullResult();
		return $res;
    }
    
    public function suppAnnonce($id) // Suppression d'une annonce d'identifiant $id
	{
		$qb = $this->createQueryBuilder('a');
		$query = $qb->delete('App\Entity\Annonce', 'a')
		  ->where('a.id = :id')
		  ->setParameter('id', $id);
		
		return $qb->getQuery()->getResult();
	}
}
