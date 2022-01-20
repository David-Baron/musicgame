<?php 
namespace App\Controller;

use App\Service\GithubService;
use App\Repository\SettingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    protected $module = 'admin';
    protected SettingRepository $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }
    
    /**
     * @Route("/admin", name="admin_panel", methods={"GET"})
     */
    public function admin()
    {   
        $this->denyAccessUnlessGranted('ROLE_MODO');
        return $this->redirectToRoute('admin_musicgame_list');
    }
    
    /**
     * @Route("/admin/about", name="admin_about", methods={"GET"})
     */
    public function about(GithubService $service)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $update = $this->checkForUpdate($service);
        $application = [
            'owner' => $this->getParameter('app.owner'),
            'name' => $this->getParameter('app.name'),
            'version' => $this->getParameter('app.version'),
            'hasUpdate' => $update
        ];
        return $this->returnView('domain/about.html.twig', [
                'application' => $application
        ]);
    }

    public function returnView(string $template, array $data = [])
    {
        return $this->render('default/admin/' . $template, $data);
    }

    protected function checkForUpdate($service)
    {
        $current = $this->getParameter('app.version');
        $latest = $service->latestReleaseVersion();
        if ($latest) {
            if (version_compare($current, $latest, '<')) {
                return true;
            }
        }
        return false;
    }

}