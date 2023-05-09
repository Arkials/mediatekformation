<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Classe de tests sur playlistrepository
 *
 * @author Arkials
 */


class PlaylistRepositoryTest extends KernelTestCase {

    CONST NOM_PLAYLIST ="testtest";

    
    /**
     * Repository qui sera réutilisé 
     * @return PlaylistRepository
     */
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Playlist qui sera utilisée
     * @return Formation
     */
    public function recupPlaylist(): Playlist{      
        $playlist = (new Playlist())
                ->setName(SELF::NOM_PLAYLIST);                                
        return $playlist;        
    }
    
    /**
     * test sur l'ajout d'une playlist
     */
    public function testAddPlaylist(){
        $repository= $this-> recupRepository();
        $nbPlaylists = $repository->count([]);

        $playlist = $this->recupPlaylist();
        $repository->add($playlist,true);
        $this->assertEquals($nbPlaylists+1,$repository->count([]),"Échec de l'ajout d'une playlist" );  
    }
    
    /***
     * Test suppression playlist
     */
    public function testSupprPlaylist(){
        $repository= $this-> recupRepository();
        $playlist = $this->recupPlaylist();
        $repository->add($playlist,true);
        $nbPlaylists = $repository->count([]);      
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists-1,$repository->count([]),"Échec de la suppression d'une playlist" );                
    }
    
    /**
     * Test sur récupération de tous les enregistrements et tri sur le nom
     */
    public function testfindAllOrderByName(){
        $repository = $this->recupRepository();
        $playlists_ordre_asc_titre = $repository->findAllOrderByName("asc");
        $this->assertEquals("Bases de la programmation (C#)", ($playlists_ordre_asc_titre[0])->getName());                   
    }
    
    /**
     * Test sur récupération de tous les enregistrements et tri sur le nombre de formations
     */
    public function testfindAllOrderByNbFormations(){
        $repository = $this->recupRepository();
        $playlists_ordre_asc_titre = $repository->findAllOrderByNbFormations("asc");
        $this->assertEquals("Cours Informatique embarquée", ($playlists_ordre_asc_titre[0])->getName());                   
    }
    
    /**
     * Test sur récupération d'une playlist avec recherche sur un champs
     */
    public function testfindByContainValueSameTable() {
        $repository = $this->recupRepository();
        $playlist = $this->recupPlaylist();
        $repository->add($playlist,true);
        $playlists = $repository->findByContainValueSameTable("name","testtest");
        $nbPlaylists = count($playlists);
        $this->assertEquals(1, $nbPlaylists, "La playlist n'a pas été retrouvée");        
        $this->assertEquals(self::NOM_PLAYLIST, $playlists[0]->getName(), "Ce n'est pas la bonne playlist");                            
    }
    
    /**
     * Test de recherche sur une autre table
     */
    public function testFindByContainValueDifferentTable(){
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValueDifferentTable("name","Java");
        $this->assertEquals("Eclipse et Java", $playlists[0]->getName(), "Ce n'est pas la bonne playlist");        
    }
    
    
    
}
