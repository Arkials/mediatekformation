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
    
    /**
     * Test sur les tris de la page
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
    $this->assertSelectorTextContains('h5', 'Eclipse n°6 : Documentation technique', "Problème au niveau du tri"); 
    }
    
    public function testFiltreFormation(){
        
    }
}
