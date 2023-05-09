<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Repository;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategoriesRepositoryTest
 *
 * @author Arkials
 */
class CategoriesRepositoryTest extends KernelTestCase {
    
     CONST NOM_CATEGORIE ="testtest";

    
    /**
     * Repository qui sera réutilisé 
     * @return CategorieRepository
     */
    public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Categorie qui sera utilisée
     * @return Formation
     */
    public function recupCategorie(): Categorie{      
        $categorie = (new Categorie())
                ->setName(SELF::NOM_CATEGORIE);                                
        return $categorie;        
    }
    
    /**
     * test sur l'ajout d'une categorie
     */
    public function testAddCategorie(){
        $repository= $this-> recupRepository();
        $nbCategories = $repository->count([]);

        $categorie = $this->recupCategorie();
        $repository->add($categorie,true);
        $this->assertEquals($nbCategories+1,$repository->count([]),"Échec de l'ajout d'une categorie" );  
    }
    
    /***
     * Test suppression categorie
     */
    public function testSupprCategorie(){
        $repository= $this-> recupRepository();
        $categorie = $this->recupCategorie();
        $repository->add($categorie,true);
        $nbCategories = $repository->count([]);      
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories-1,$repository->count([]),"Échec de la suppression d'une categorie" );                
    }
    
    /**
     * Test sur la liste des catégories des formations d'une playlist
     */
    public function testFindAllForOnePlaylist(){
        $repository= $this-> recupRepository();
        $categoriesPlaylist = $repository->findAllForOnePlaylist(1);
        $this->assertEquals(count($categoriesPlaylist),2,"Échec de la récupération des catégories des formations de la playlist 1 (java)" );  
    }

     /**
      * Test vérification du nom
      */
      public function testCheckNameAvailable(){
        $repository= $this-> recupRepository();
        $categorie = $this->recupCategorie();
        $repository->add($categorie,true);
        $nomPresent=$repository->checkNameAvailable($categorie);
        $this->assertEquals(false,$nomPresent,"Échec de la vérification, le nom est pourtant présent" );      
    }
}
