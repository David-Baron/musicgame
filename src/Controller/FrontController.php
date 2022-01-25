<?php

namespace App\Controller;

use App\Repository\SettingRepository;
use App\Repository\MusicgameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    protected $module = 'front';
    protected SettingRepository $settingRepository;
    private MusicgameRepository $musicgameRepository;

    public function __construct(SettingRepository $settingRepository, MusicgameRepository $musicgameRepository)
    {
        $this->settingRepository = $settingRepository;
        $this->musicgameRepository = $musicgameRepository;

    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {
        $musicgames = $this->musicgameRepository->findAll();
        return $this->returnView('index.html.twig', [
            'musicgames' => $musicgames
        ]);
    }

    public function viewTrackList(Request $request)
    {
        $musicgame = $this->musicgameRepository->findOneBy(['slug' => $request->get('slug')]);
        //dd($musicgame->getTracks());
        return $this->returnView('musicgame-track-table.html.twig', [
            'musicgame' => $musicgame
        ]);
    }
    
    public function viewPlaylistTable(Request $request)
    {
        $playlist = $this->musicgamePlaylistRepository->find($request->get('playlistId'));
        return $this->returnView('musicgame-playlist-table.html.twig', [
            'playlist' => $playlist
        ]);
    }

    public function returnView(string $template, array $data = [])
    {
        $data['domain'] = $this->settingRepository->findOneBy(['name' => 'website_domain'])->getValue();
        $data['sitename'] = $this->settingRepository->findOneBy(['name' => 'website_name'])->getValue();
        $data['icon'] = $this->settingRepository->findOneBy(['name' => 'website_icon'])->getValue();
        
        return $this->render($this->settingRepository->findOneBy(['name' => 'website_templates'])->getValue() . '/front/' . $template, $data);
    }
}