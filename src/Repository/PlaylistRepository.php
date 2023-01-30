<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    Const PLAYLIST_ID = "p.id";
    Const PLAYLIST_ID_ID = "p.id id";
    Const PLAYLIST_NAME_NAME= "p.name name";
    Const PLAYLIST_NAME="p.name";
    Const PLAYLIST_FORMATION = "p.formations";
    Const CATEGORY_NAME ="c.name";
    Const CATEGORY_NAME_CATEGORIE= "c.name categoriename";
    Const FORMATION_CATEGORIES ="f.categories";
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist 
     * @param type $ordre 
     * @return Playlist[] 
     */ 
    public function findAllOrderByName($ordre): array {
        return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->groupBy('p.id')
                    ->orderBy('p.name', $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**     
     * Retourne toutes les playlists triées sur le nombre de formations 
     * @param type $ordre 
     * @return Playlist[]
     */

    public function findAllOrderByNbFormations($ordre): array {
        return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->groupBy('p.id')
                    ->orderBy('count(f.title)', $ordre)
                    ->getQuery()
                    ->getResult();
    }    
    public function findByContainValueSameTable($champ, $valeur): array {
        return $this
                    ->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.' . $champ . ' LIKE :valeur')
                    ->setParameter('valeur', '%' . $valeur . '%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
        
    }
    public function findByContainValueDifferentTable($champ, $valeur): array {
        return $this->createQueryBuilder('p')
           ->leftjoin('p.formations', 'f')
           ->leftjoin('f.categories', 'c')
           ->where('c.' . $champ . ' LIKE :valeur')
           ->setParameter('valeur', '%' . $valeur . '%')
           ->groupBy('p.id')
           ->orderBy('p.name', 'ASC')
           ->getQuery()
           ->getResult();
    }


}
