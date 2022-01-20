<?php 
namespace App\Controller\Musicgame;

use App\Controller\AdminController;
use App\Entity\MusicgameTrack;
use App\Form\MusicgameTrackType;
use App\Repository\MusicgameRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MusicgameTrackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MusicgameTrackController extends AdminController
{

    private MusicgameRepository $musicgameRepository;
    private MusicgameTrackRepository $musicgameTrackRepository;
    private EntityManagerInterface $em;

    public function __construct(MusicgameRepository $musicgameRepository, MusicgameTrackRepository $musicgameTrackRepository, EntityManagerInterface $em)
    {
        $this->musicgameRepository = $musicgameRepository;
        $this->musicgameTrackRepository = $musicgameTrackRepository;
        $this->em = $em;
    }
    
    /**
     * @Route("/admin/musicgames/{slug}/tracks", name="admin_musicgame_game_track_list", methods={"GET","POST"})
     */
    public function trackList(Request $request)
    {
        $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
        $newTrack = new MusicgameTrack();
        $newTrack->setMusicgame($musicgame);
        $trackForm = $this->createForm(MusicgameTrackType::class, $newTrack);
        $trackForm->handleRequest($request);
        if ($trackForm->isSubmitted() && $trackForm->isValid()) {
            $newTrack = $trackForm->getData();
            $newTrack->setIsOnline(1);
            $newTrack->setFullname($newTrack->getArtist() . ' - ' . $newTrack->getTitle());
            $this->em->persist($newTrack);
            $this->em->flush();
            return $this->redirectToRoute('admin_musicgame_game_track_list', ['slug' => $musicgame->getSlug()]);
        }
        return $this->returnView('musicgame/game-track-table.html.twig', [
            'musicgame' => $musicgame,
            'trackForm' => $trackForm->createView()
        ]);
    }

    /**
     * @Route("/admin/musicgames/{slug}/tracks/track-ajax-update", name="admin_musicgame_game_track_update", methods={"POST"})
     */
    public function ajaxTrackUpdate(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data['id']) {
            $track = $this->musicgameTrackRepository->find($data['id']);
            if (!$track) {
                $callback = [
                    'success' => false,
                    'status' => 'Not Found',
                    'code' => 404,
                    'message' => 'This track does not exist'
                ];
                return $this->json($callback, 404);
            }
            $track->setTitle($data['title']);
            $track->setIsOnline($data['isOnline']);
            $track->setFullname($track->getArtist() . ' - ' . $track->getTitle());
            $this->em->persist($track);
            $this->em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The track is modified'
            ];
            return $this->json($callback, 200, ['groups' => 'trackOnly']);
        }
    }

    /**
     * @Route("/admin/musicgames/{slug}/tracks/track-ajax-delete", name="admin_musicgame_game_track_delete", methods={"POST"})
     */
    public function ajaxTrackDelete(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data['id']) {
            $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
            $track = $this->musicgameTrackRepository->find($data['id']);
            if (!$track) {
                $callback = [
                    'success' => false,
                    'status' => 'Not Found',
                    'code' => 404,
                    'message' => 'This track does not exist'
                ];
                return $this->json($callback, 404);
            }
            $musicgame->removeTrack($track);
            $this->em->remove($track);
            $this->em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The track is deleted'
            ];
            return $this->json($callback, 200);
        }
    }
}