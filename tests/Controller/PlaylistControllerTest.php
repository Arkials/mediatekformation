<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistControllerTest
 *
 * @author Arkials
 */
class PlaylistControllerTest extends WebTestCase {
    
    public CONST BASE_C = "Bases de la programmation (C#)";
    public CONST NB_PLAYLISTS = "Problème nombre de playlists";
    
    /**
     * Test sur les tris de la page Playlists
     */
    public function testTriPlaylist () {
    $client = static::createClient();
    $client->request('GET', '/playlists/tri/name/ASC');
    $this->assertSelectorTextContains('h5', self::BASE_C, "Problème au niveau du tri playlist asc");
    $client->request('GET', '/playlists/tri/name/DESC');
    $this->assertSelectorTextContains('h5', 'Visual Studio 2019 et C#', "Problème au niveau du tri playlist desc");  
    $client->request('GET', '/playlists/tri/nbformations/ASC');
    $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée', "Problème au niveau du tri"); 
    $client->request('GET', '/playlists/tri/nbformations/DESC');
    $this->assertSelectorTextContains('h5', self::BASE_C, "Problème au niveau du tri"); 
    }
    
    /**
     * Test sur les filtres de la page Playlists
     */
    public function testFiltrePlaylist(){
        $client = static::createClient();
        $crawlerPagePlaylist = $client->request('GET','/playlists');
        
        $crawlerRecherchePlaylist= $client->submitForm('filtrer', [
            'recherche'=>'Eclipse'
        ]);
        $this->assertSelectorTextContains('td', "Eclipse et Java","Problème niveau recherche playlist");
        $this->assertCount(1+1, $crawlerRecherchePlaylist->filter('tr'), self::NB_PLAYLISTS);                
        
        $formRechercheCategorie = $crawlerPagePlaylist->filterXPath('//form[@id="cat_recherche"]')->form([
            'recherche' => '2',
        ]);
        $crawlerRechercheCategorie = $client->submit($formRechercheCategorie);

        $this->assertCount(2+1, $crawlerRechercheCategorie->filter('tr'),self::NB_PLAYLISTS);
        $this->assertSelectorTextContains('td', 'Cours UML','Problème niveau recherche catégorie');        
    }
    
    /**
     * Test sur les liens de playlist
     */
    public function testLienVideoPlaylist() {
        $client = static::createClient();
        $client->request('GET','playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode(),"Problème niveau lien playlist");
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals($uri, "/playlists/playlist/13","Problème niveau lien playlist");
        $this->assertSelectorTextContains('h4', self::BASE_C,"Problème pas la bonne playlist");
    }
}
