<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests;

use App\Entity\Formation;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Classe de tests sur les contraintes de date de formation
 * @author Arkials
 */
class FormationValidationTest extends KernelTestCase {
    public function getFormation() {
        return (new Formation())->setTitle("test")->setVideoId("testvideoid");
    }
    
    /**
     * Fonction vérifiant et comptant le nombre d'erreurs attendus
     * @param Formation $formation
     * @param int $nbErreursAttendues
     */
    public function assertErrors (Formation $formation,int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
    
    /**
     * Test avec une date antérieur à aujourd'hui (doit réussir)
     */
    public function testValidPublishedAt(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("now-1day"));
        $this->assertErrors($formation, 0);
    }
    
    /**
     * Test avec une date postérieur à aujourd'hui (doit échouer)
     */
    public function testUnvalidPublishedAt(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("now+1day"));
        $this->assertErrors($formation, 1);
    }
}
