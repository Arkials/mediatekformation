<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Repository;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
/**
 * Classe de tests sur le repository formations
 * @author Arkials
 */
class FormationRepositoryTest extends KernelTestCase{
    
    CONST NOM_FORMATION = "testtest";
    /**
     * Formation qui sera utilisée
     * @return Formation
     */
    public function recupFormation(): Formation{
        $repository_categories = self::getContainer()->get(CategorieRepository::class);
        $repository_playlists = self::getContainer()->get(PlaylistRepository::class);        
        $formation = (new Formation())
                ->setTitle(SELF::NOM_FORMATION)
                ->addCategory($repository_categories->find(1))
                ->setPublishedAt(new \DateTime("now"))
                ->setPlaylist($repository_playlists->find(1));
        return $formation;        
    }
    
    /**
     * Repository qui sera réutilisé 
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    /**
     * Test sur l'ajout d'une formation
     */
    public function testAjoutFormation(){
        $repository= $this-> recupRepository();
        $nbFormations = $repository->count([]);

        $formation = $this->recupFormation();
        $repository->add($formation,true);
        $this->assertEquals($nbFormations+1,$repository->count([]),"Échec de l'ajout d'une formation" );                
    }
    
    /***
     * Test suppression formation
     */
    public function testSupprFormation(){
        $repository= $this-> recupRepository();
        $formation = $this->recupFormation();
        $repository->add($formation,true);
        $nbFormations = $repository->count([]);      
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations-1,$repository->count([]),"Échec de la suppression d'une formation" );                

    }    
    
    /**
     * Test sur récupération de tous les enregistrements et tri avec élément de la même table
     */
    public function findAllOrderByDifferentTable(){
        $repository = $this->recupRepository();
        $formations_ordre_asc_titre = $repository->findAllOrderByDifferentTable("name", "asc","playlist");
        $this->assertEquals("Bases de la programmation n°74 - POO : collections", ($formations_ordre_asc_titre[0])->getTitle());
        
    }
    
    /**
     * Test sur récupération de tous les enregistrements et tri avec élément de la même table
     */
    public function findAllOrderBySameTable(){
        $repository = $this->recupRepository();
        $formations_ordre_asc_titre = $repository->findAllOrderBySameTable("title", "asc");
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", ($formations_ordre_asc_titre[0])->getTitle());        
    }     
    
    /**
     * Test sur le nombre de formations
     */
    public function testNbFormations(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->AssertEquals(234,$nbFormations,"le nombre de formations n'est pas bon");
    }
    
    /**
     * Test de recherche d'une valeur
     */
    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $formation = $this->recupFormation();
        $repository->add($formation,true);
        $formations = $repository->findByContainValue("title","testtest");
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations, "La formation n'a pas été retrouvée");        
        $this->assertEquals('testtest', $formations[0]->getTitle(), "Ce n'est pas la bonne formation");                      
    }
    /**
     * Test de recherche sur une autre table
     */
    public function testFindByContainValueDifferentTable(){
        $repository = $this->recupRepository();
        $formation = $this->recupFormation();
        $repository->add($formation,true);
        $formations = $repository->findByContainValueDifferentTable("name","Java","categories");
        $this->assertEquals(self::NOM_FORMATION, $formations[0]->getTitle(), "Ce n'est pas la bonne formation");        
    }
    
    /**
     * Test recherche des 3 dernières formations
     */
    public function testFindAllLasted(){
        
        $repository = $this->recupRepository();
        $formations=[$this->recupFormation()->setTitle(SELF::NOM_FORMATION.'1'),$this->recupFormation()->setTitle(SELF::NOM_FORMATION.'2'),$this->recupFormation()->setTitle(SELF::NOM_FORMATION.'3')];
        foreach ($formations as $value) {           
            $repository->add($value,true);
        }
        $formations_recuperees=$repository->findAllLasted(3);
        $this->assertEquals($formations_recuperees,$formations);
    }
    
    /**
     * Test de la fonction de recherche par playlist
     */
    public function testfindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formation= $this->recupFormation();
        $repository->add($formation,true);                
        $formations_java=$repository->findAllForOnePlaylist(1);
        $nb_formations_java=count($formations_java);        
        $this->assertEquals($formation, $formations_java[$nb_formations_java-1]);
    }
    
    
}
