<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de tests sur Users
 *
 * @author Arkials
 */
class UserRepositoryTest extends KernelTestCase{
    
    public function recupRepository():UserRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(UserRepository::class);
        return $repository;
    }
    /**
     * Test sur l'ajout d'un user
     */
    public function testAddUser(){
        $repository= $this-> recupRepository();
        $nbUsers = $repository->count([]);

        $user = (new User())
                ->setUsername("testest")
                ->setPassword("testest");
        $repository->add($user,true);
        $this->assertEquals($nbUsers+1,$repository->count([]),"Échec de l'ajout d'une user" ); 
        
    }
    
    /**
     * Test sur la suppression d'un user
     */
    public function testRemoveUser(){
        $user = (new User())
                ->setUsername("testest")
                ->setPassword("testest");
        $repository= $this-> recupRepository();
        $repository->add($user,true);
        $nbUsers = $repository->count([]);
        $repository->remove($user,true);
        
        $this->assertEquals($nbUsers-1,$repository->count([]),"Échec de l'ajout d'une user" );         
    }
    
    /**
     * Test sur l'update du password
     */
    public function testUpdatePassword(){
        $user = (new User())
                ->setUsername("testest")
                ->setPassword("testest");
        $userMdp = $user->getPassword();
        $repository= $this-> recupRepository();
        $repository->add($user,true);
        $user->setPassword("test2");
        $this->assertNotEquals($user->getPassword(),$userMdp,"Échec du changement de mdp" );         

    }
        
}
