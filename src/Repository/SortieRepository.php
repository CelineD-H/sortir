<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
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

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function filtreSorties(mixed $filtres, int $userId)
    {
        $req = $this->createQueryBuilder('s');

        if($filtres['campus']) {
            $req->andWhere('s.campus = :id')
                ->setParameter('id', $filtres['campus']);
        }

        if ($filtres['nom']) {
            $req->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', "%{$filtres['nom']}%");
        }

        if ($filtres['orga']) {
            $req->andWhere('s.organisateur = :id')
                ->setParameter('id', $userId);
        }

        if ($filtres['isInscrit']) {
            $req->join('s.participants', 'p')
                ->andWhere('p.id = :id')
                ->setParameter('id', $userId);
        }

        if ($filtres['noInscrit']) {
            $req->join('s.participants', 'np')
                ->andWhere('np.id != :id')
                ->setParameter('id', $userId);
        }

        if ($filtres['passees']) {
            $req->join('s.etat', 'etat')
                ->andWhere('etat.id = 5');
        }

        $query = $req->getQuery();
        return $query->getResult();
    }
}