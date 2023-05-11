<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of FormationControllerTest
 *
 * @author Arkials
 */

/**
 * Tests sur la page des formations
 */
class FormationControllerTest extends WebTestCase{
    public CONST ECLIPSE_6 = "Eclipse n°6 : Documentation technique";
    public CONST NB_FORMATIONS = "Problème nombre de formations";
    
    /**
     * Test sur les tris de la page Formations
     */
    public function testTriFormation () {
    $client = static::createClient();
    $client->request('GET', '/formations/tri/title/ASC');
    $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment', "Problème au niveau du tri formation asc");
    $client->request('GET', '/formations/tri/title/DESC');
    $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages', "Problème au niveau du tri formation desc");  
    $client->request('GET', '/formations/tri/name/ASC/playlist');
    $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections', "Problème au niveau du tri playlist asc");  
    $client->request('GET', '/formations/tri/name/DESC/playlist');
    $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur', "Problème au niveau du tri playlist desc");    
    $client->request('GET', '/formations/tri/publishedAt/ASC');
    $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation', "Problème au niveau du tri"); 
    $client->request('GET', '/formations/tri/publishedAt/DESC');
    $this->assertSelectorTextContains('h5', self::ECLIPSE_6, "Problème au niveau du tri"); 
    }
    
    /**
     * Test sur les filtres de la page Formations
     */
    public function testFiltreFormation(){
        $client = static::createClient();
        $crawlerPageFormation = $client->request('GET','/formations');
        
        $crawlerRechercheFormation= $client->submitForm('filtrer', [
            'recherche'=>'Eclipse'
        ]);
        $this->assertSelectorTextContains('td', self::ECLIPSE_6,"Problème niveau recherche formation");
        $this->assertCount(7+1, $crawlerRechercheFormation->filter('tr'), self::NB_FORMATIONS);
                

        $formRecherchePlaylist = $crawlerPageFormation->filterXPath('//form[@id="play_recherche"]')->form([
            'recherche' => 'Java',
        ]);
        $crawlerRecherchePlaylist = $client->submit($formRecherchePlaylist);
        $this->assertSelectorTextContains('td', self::ECLIPSE_6, 'Problème niveau recherche formation');
        $this->assertCount(12 + 1, $crawlerRecherchePlaylist->filter('tr'),self::NB_FORMATIONS);
        
        $formRechercheCategorie = $crawlerPageFormation->filterXPath('//form[@id="cat_recherche"]')->form([
            'recherche' => '3',
        ]);
        $crawlerRechercheCategorie = $client->submit($formRechercheCategorie);

        $this->assertCount(85+1, $crawlerRechercheCategorie->filter('tr'),self::NB_FORMATIONS);
        $this->assertSelectorTextContains('td', 'C# : ListBox en couleur','Problème niveau recherche catégorie');        
    }
    
    /**
     * Test sur les liens de formation
     */
    public function testLienVideoFormation() {
        $client = static::createClient();
        $client->request('GET','formations');
        $client->clickLink("image formation");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode(),"Problème niveau lien formation");
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals($uri, "/formations/formation/3","Problème niveau lien formation");
        $this->assertSelectorTextContains('h4', self::ECLIPSE_6,"Problème pas la bonne formation");
    }
}
