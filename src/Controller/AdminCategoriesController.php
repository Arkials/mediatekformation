<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Description of CategoriesController
 *
 * @author Arkials
 */
class AdminCategoriesController extends AbstractController {
    
    const PCATEGORIES="admin/admin.categories.html.twig";
    CONST R_CATEGORIES_ADMIN="categories";
    
    /**
     * Repository des catégories
     * @var CategorieRepository 
     */
    private $categorieRepository;
    
    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affiche la page et valide le formulaire si rempli de façon valide
     * @Route("admin/categories", name="admin.categories")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response{
        
        $categories = $this->categorieRepository->findAll();
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formCategorie->handleRequest($request);

         if($formCategorie->isSubmitted() && $formCategorie->isValid()){
             if(!$this->categorieRepository->checkNameAvailable($categorie)){
                $this->addFlash('error', 'Ce nom de catégorie existe déjà.');                  
             }
             else
             {
                $this->categorieRepository->add($categorie, true);
                return $this->redirectToRoute(self::R_CATEGORIES_ADMIN);
             }
         }
        return $this->render(self::PCATEGORIES, [
            'categories' => $categories,
            'categorie' => $categorie,
            'formCategorie' => $formCategorie->createView()
            
        ]);
    }

    
   
    
    
    
    /**
      * Méthode de suppression d'une catégorie
      * @Route("/admin/categories/suppr/{id}", name="admin.categories.suppr")
      * @param Categorie $categorie
      * @return Response
      */
    public function suppr(Categorie $categorie): Response{
         
        if ($categorie->getFormations()->count()>0){
            $this->addFlash('error', 'Impossible de supprimer une catégorie liée à des formations');            
        }
        else{
                $this->categorieRepository->remove($categorie, true);

        }
        return $this->redirectToRoute(self::R_CATEGORIES_ADMIN);
    }    
}
