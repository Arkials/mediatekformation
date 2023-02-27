<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
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
class AdminPlaylistsController extends AbstractController {
    
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
    private const P_PLAYLISTS_ADMIN = "admin/admin.playlists.html.twig";
    private const P_PLAYLIST_AJOUT="admin/admin.playlist.ajout.html.twig";
    private const R_PLAYLIST_AJOUT="admin.playlist.ajout";
    private const P_PLAYLIST_EDIT="admin/admin.playlist.edit.html.twig";
    private const R_PLAYLISTS_ADMIN= "admin.playlists";
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(Request $request): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::P_PLAYLISTS_ADMIN, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    
    /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::R_PLAYLISTS_ADMIN);
        }     

        return $this->render(self::P_PLAYLIST_AJOUT, [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);        
    }    
    
    
    
    /**
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $playlist, Request $request): Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);

        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::R_PLAYLISTS_ADMIN);
        }     

        return $this->render(self::P_PLAYLIST_EDIT, [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);        
}

/**
      * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
      * @param Playlist $playlist
      * @return Response
      */
    public function suppr(Playlist $playlist): Response{
        if ($playlist->getFormations()->count()>0){
            $this->addFlash('error', 'Impossible de supprimer une playlist liée à des formations');            
        }
        else{
            $this->playlistRepository->remove($playlist, true);
        }
        return $this->redirectToRoute(self::R_PLAYLISTS_ADMIN);
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
            default:
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);

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
    
        

    
    
}
