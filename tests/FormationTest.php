<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Formation;

/**
 * Classe testant la validité de la transformation du datetime en string
 *
 * @author Arkials
 */
class FormationTest extends TestCase{
    public function testGetDateCreationString(){
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2023-05-07"));
        $this->assertEquals("07/05/2023", $formation->getPublishedAtString());
    }
    
}
