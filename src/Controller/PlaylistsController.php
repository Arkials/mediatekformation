<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsController extends AbstractController {
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;   
    
    /**
      Chaine pour la page des playlist.
     */
    private const PPLAYLISTS = "pages/playlists.html.twig";
    private const PPLAYLIST = "pages/playlist.html.twig";
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }

    /**
     * @Route("/playlists/tri/{champ}/{ordre}", name="playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name": 
                    $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                    $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break; 
            
        }
                
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }         
    
    /**
     * @Route("/playlists/recherche/{champ}/{table}", name="playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{        
        $valeur = $request->get("recherche");
        if($valeur=="")
        {
           $playlists = $this->playlistRepository->findAllOrderByName('ASC');            
        }
        elseif($table==""){            
            $playlists = $this->playlistRepository->findByContainValueSameTable($champ, $valeur);  
        }            
        else{
            $playlists = $this->playlistRepository->findByContainValueDifferentTable($champ, $valeur, $table);
        }
        
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
        

    /**
     * @Route("/playlists/playlist/{id}", name="playlists.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::PPLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);        
    }       
    
}
