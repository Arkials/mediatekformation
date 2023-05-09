<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    CONST DATE_PUBLICATION ='f.publishedAt';
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function add(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retourne toutes les formations triées sur un champ d'une table autre que formation
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Formation[]
     */
    public function findAllOrderByDifferentTable($champ, $ordre, $table): array{
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();            
        }    
    
        /**
         * Retourne les formations triées sur le champ de la table formation
         * @param type $champ
         * @param type $ordre
         * @return array
         */
    public function findAllOrderBySameTable($champ,$ordre): array{
        
        return $this->createQueryBuilder('f')
                   ->orderBy('f.'.$champ, $ordre)
                   ->getQuery()
                   ->getResult();        
    }   
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur): array{
        
        return $this->createQueryBuilder('f')
                ->where('f.'.$champ.' LIKE :valeur')
                ->orderBy(self::DATE_PUBLICATION, 'DESC')
                ->setParameter('valeur', '%'.$valeur.'%')
                ->getQuery()
                ->getResult();                          
        }       
        
        /**
         * Enregistrements dont un champ, d'une autre table, contient une valeur
         * @param type $champ
         * @param type $valeur
         * @param type $table
         * @return array
         */
    public function findByContainValueDifferentTable($champ, $valeur, $table): array{
        
        return $this->createQueryBuilder('f')
            ->join('f.'.$table, 't')                    
            ->where('t.'.$champ.' LIKE :valeur')
            ->orderBy(self::DATE_PUBLICATION, 'DESC')
            ->setParameter('valeur', '%'.$valeur.'%')
            ->getQuery()
            ->getResult();           
    }
    
    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
                ->orderBy(self::DATE_PUBLICATION, 'DESC')
                ->setMaxResults($nb)     
                ->getQuery()
                ->getResult();
    }    
    
    /**
     * Retourne la liste des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy(self::DATE_PUBLICATION, 'ASC')   
                ->getQuery()
                ->getResult();        
    }
    
}
