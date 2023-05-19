<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 */
class AdminFormationsController extends AbstractController {

    /**
     * Repository des formations
     * @var FormationRepository
     */
    private $formationRepository;
    
    private const P_FORMATIONS_ADMIN = "admin/admin.formations.html.twig";
    private const P_FORMATION_AJOUT="admin/admin.formation.ajout.html.twig";
    private const P_FORMATION_EDIT="admin/admin.formation.edit.html.twig";
    private const R_FORMATIONS_ADMIN= "admin.formations";
    
    /**
     * Repository des catégories
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * Affichage de la page de gestion des formations
     * @Route("/admin/formations", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::P_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * Affichage de la page d'ajout et validation d'un ajout si formulaire correct
     * @Route("/admin/formation/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::R_FORMATIONS_ADMIN);
        }     

        return $this->render(self::P_FORMATION_AJOUT, [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);        
    }    
    
      /**
       * Méthode de suppression d'une formation
        * @Route("/admin/formations/suppr/{id}", name="admin.formations.suppr")
        * @param Formation $formation
        * @return Response
      */
    public function suppr(Formation $formation): Response{
        $this->formationRepository->remove($formation, true);
        return $this->redirectToRoute(self::R_FORMATIONS_ADMIN);
    }

    /**
     * Méthode de tri sur les formations en fonction de la table, du champs et de l'ordre
     * @Route("admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        if($table==""){
            $formations = $this->formationRepository->findAllOrderBySameTable($champ, $ordre);
        }
        else{
            $formations = $this->formationRepository->findAllOrderByDifferentTable($champ, $ordre, $table);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::P_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
   /**
     * Méthode d'affichage de la page de modification d'une formation
     * et validation de la modification d'une formation 
     * @Route("/admin/formations/edit/{id}", name="admin.formations.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);

        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::R_FORMATIONS_ADMIN);
        }     

        return $this->render(self::P_FORMATION_EDIT, [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);        
    }
        
    /**
     * Méthode de recherche sur le nom d'une formation
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontainadmin")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContainAdmin($champ, Request $request, $table=""): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){

            $valeur = $request->get("recherche");
            if($table==""){
                $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            }
            else{
                $formations = $this->formationRepository->findByContainValueDifferentTable($champ, $valeur, $table);
            }
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::P_FORMATIONS_ADMIN, [
                'formations' => $formations,
                'categories' => $categories,
                'valeur' => $valeur,
            ]);
        }
        return $this->redirectToRoute(self::R_FORMATIONS_ADMIN);
        
    }
    
    
    /**
     * Méthode d'affichage des détails d'une formation
     * @Route("admin/formations/formation/{id}", name="admin.formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render(self::P_FORMATIONS_ADMIN, [
            'formation' => $formation
        ]);        
    }   
    
}
