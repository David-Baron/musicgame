<?php 
namespace App\Controller\Musicgame;

use App\Controller\AdminController;
use App\Repository\MusicgameRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MusicgameTrackRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MusicgamePlaylistRepository;
use Symfony\Component\Routing\Annotation\Route;

class MusicgamePlaylistController extends AdminController
{
    private MusicgameRepository $musicgameRepository;
    private MusicgamePlaylistRepository $musicgamePlaylistRepository;
    private EntityManagerInterface $em;

    public function __construct(MusicgameRepository $musicgameRepository, MusicgamePlaylistRepository $musicgamePlaylistRepository, EntityManagerInterface $em)
    {
        $this->musicgameRepository = $musicgameRepository;
        $this->musicgamePlaylistRepository = $musicgamePlaylistRepository;
        $this->em = $em;
    }
    /**
     * @Route("/admin/musicgames/{slug}/playlists/{id}/tracks", name="admin_musicgame_game_playlist_edit", methods={"GET","POST"})
     */
    public function tracks(Request $request)
    {
        $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
        $playlist = $this->musicgamePlaylistRepository->find($request->get('id'));
        
        return $this->returnView('musicgame/game-playlist-table.html.twig', [
            'musicgame' => $musicgame,
            'playlist' => $playlist
        ]);
    }

    /**
     * @Route("/admin/musicgames/{slug}/playlists/{id}-delete", name="admin_musicgame_game_playlist_delete", methods={"GET"})
     * TODO : will be with POST method.
     */
    public function delete(Request $request)
    {
        $playlist = $this->musicgamePlaylistRepository->find($request->get('id'));
        $this->em->remove($playlist);
        $this->em->flush();
        $this->addFlash('success', 'The playlist is removed!');
        return $this->redirectToRoute('musicgame/admin_musicgame_game_dashboard');
    }

    /**
     * @Route("/admin/musicgames/{slug}/playlists/{id}-ajax_add_tracks", name="playlist_ajax_add_tracks", methods={"POST"})
     */
    public function ajaxAddTracks(Request $request, MusicgameTrackRepository $musicgameTrackRepository)
    {
        if ($request->get('id')) {
            $playlist = $playlist = $this->musicgamePlaylistRepository->find($request->get('id'));
            $data = json_decode($request->getContent(), true);
            foreach ($data as $track) {
                $rtrack = $musicgameTrackRepository->find($track['id']);
                $playlist->addTrack($rtrack);
            }
            $this->em->persist($playlist);
            $this->em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The playlist is modified'
            ];
            return $this->json($callback, 200);
        }
    }

    /**
     * @Route("/admin/musicgames/{slug}/playlists/{id}-ajax_delete_tracks", name="playlist_ajax_delete_tracks", methods={"POST"})
     */
    public function ajaxDeleteTrack(Request $request, MusicgameTrackRepository $musicgameTrackRepository)
    {
        if ($request->get('id')) {
            $playlist = $this->musicgamePlaylistRepository->find($request->get('id'));
            $data = json_decode($request->getContent(), true);
            $rtrack = $musicgameTrackRepository->find($data['id']);
            $playlist->removeTrack($rtrack);
            $this->em->persist($playlist);
            $this->em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The playlist is modified'
            ];
            return $this->json($callback, 200);
        }
    }
}