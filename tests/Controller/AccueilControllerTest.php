<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de test fonctionnel sur l'accueuil
 *
 * @author Arkials
 */
class AccueilControllerTest extends WebTestCase {
    
    public function testAccessAccueuil () {
    $client = static::createClient();
    $client->request('GET', '/');
    $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    
    }
    
    
    
    
}
