<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    /**
     * Repository des formations
     * @var FormationRepository
     */
    private $formationRepository;
    
    private const PFORMATIONS = "pages/formations.html.twig";
    private const RFORMATIONS ="formations";
    private const PFORMATION ="pages/formation.html.twig";
    
    /**
     * Repository des catégories
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Affichage de la page
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

   
    /**
     * Tri sur des formations selon un champ, un ordre et une table
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
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
        return $this->render(self::PFORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }        
    
    /**
     * Recherche sur les formations selon un champ et une table
     * @Route("formations/recherche/{champ}/{table}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token')) && $request->get("recherche")!=null){
            $valeur = $request->get("recherche");
            if($table==""){
                $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            }
            else{

                $formations = $this->formationRepository->findByContainValueDifferentTable($champ, $valeur, $table);
            }

            $categories = $this->categorieRepository->findAll();
            return $this->render(self::PFORMATIONS, [
                'formations' => $formations,
                'categories' => $categories,
            ]);
        }
        else{
            return $this->redirectToRoute(self::RFORMATIONS);            
        }        

    }
    
    
    /**
     * Affichage des détails d'une formation
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->render(self::PFORMATION, [
            'formation' => $formation
        ]);        
    }   
    
}
