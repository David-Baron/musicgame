<?php 
namespace App\Controller\Musicgame;

use App\Entity\Musicgame;
use App\Form\MusicgameType;
use App\Entity\MusicgamePlaylist;
use App\Controller\AdminController;
use App\Form\MusicgamePlaylistType;
use App\Repository\MusicgameRepository;
use App\Service\ImageFileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MusicgameController extends AdminController
{

    private MusicgameRepository $musicgameRepository;
    private EntityManagerInterface $em;

    public function __construct(MusicgameRepository $musicgameRepository, EntityManagerInterface $em)
    {
        $this->musicgameRepository = $musicgameRepository;
        $this->em = $em;
    }
    
    /**
     * @Route("/admin/musicgames", name="admin_musicgame_list", methods={"GET"})
     */
    public function list()
    {
        $musicgames = $this->musicgameRepository->findAll();
        
        return $this->returnView('musicgame/musicgame-list.html.twig', [
            'musicgames' => $musicgames
        ]);
    }

    /**
     * @Route("/admin/musicgames/create", name="admin_musicgame_create", methods={"GET","POST"})
     */
    public function create(Request $request,ImageFileUploader $imageFileUploader)
    {
        $newGame = new Musicgame();
        $musicgameForm = $this->createForm(MusicgameType::class, $newGame);
        $musicgameForm->handleRequest($request);
        if ($musicgameForm->isSubmitted() && $musicgameForm->isValid()) {
            $newGame = $musicgameForm->getData();
            $newGame->setSlug(strtolower(str_replace(' ', '-', $newGame->getName())));
            // TODO Handle the thumbnail
            $imageFile = $musicgameForm->get('imageFile')->getData();
            if ($imageFile) {
                $filename = $imageFileUploader->upload($imageFile);
                $newGame->setThumbnail($filename);
            }
            $this->em->persist($newGame);
            $this->em->flush();
            $this->addFlash('success', 'New musicgame created.');
            return $this->redirectToRoute('admin_musicgame_list');
        }
        return $this->returnView('musicgame/musicgame-form.html.twig', [
            'musicgameForm' => $musicgameForm->createView()
        ]);
    }

    /**
     * @Route("/admin/musicgames/{id}-delete", name="admin_musicgame_delete", methods={"POST"})
     */
    public function delete(Request $request)
    {
        $musicgame = $this->musicgameRepository->find($request->get('id'));
        $this->em->remove($musicgame);
        $this->em->flush();
        $this->addFlash('success', 'The musicgame has been deleted.');
        return $this->redirectToRoute('admin_musicgame_list');
    }

    /**
     * @Route("/admin/musicgames/{slug}", name="admin_musicgame_game_dashboard", methods={"GET","POST"})
     */
    public function gameDashboard(Request $request)
    {
        $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
        $playlist = new MusicgamePlaylist();
        $playlist->setMusicgame($musicgame);
        $playlistForm = $this->createForm(MusicgamePlaylistType::class, $playlist);
        $playlistForm->handleRequest($request);
        if ($playlistForm->isSubmitted() && $playlistForm->isValid()) {
            $playlist = $playlistForm->getData();
            $playlist->setTitle(ucfirst($playlist->getTitle()));
            $this->em->persist($playlist);
            $this->em->flush();
            return $this->redirectToRoute('admin_musicgame_game_dashboard', [ 'slug' => $musicgame->getSlug()]);
        }
        return $this->returnView('musicgame/game-dashboard.html.twig', [
            'musicgame' => $musicgame,
            'playlistForm' => $playlistForm->createView()
        ]);
    }
    
}