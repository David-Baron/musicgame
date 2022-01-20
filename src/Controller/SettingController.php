<?php 
namespace App\Controller;

use App\Controller\AdminController;
use App\Repository\SettingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AdminController
{
    protected SettingRepository $settingRepository;
    protected $doctrine;
    
    public function __construct(SettingRepository $settingRepository, ManagerRegistry $doctrine)
    {
        $this->settingRepository = $settingRepository;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/admin/settings", name="admin_setting_list", methods={"GET"})
     */
    public function list()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $settings = $this->settingRepository->findAll();
        return $this->render('default/admin/domain/setting-table.html.twig', [
            'settings' => $settings
        ]);
    }

    /**
     * @Route("/admin/settings/setting", name="admin_ajax_setting_update", methods={"POST"})
     */
    public function ajaxEdit(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $data = json_decode($request->getContent(), true);
        if ($data['id']) {
            $setting = $this->settingRepository->find($data['id']);
            if (!$setting) {
                $callback = [
                    'success' => false,
                    'status' => 'Not Found',
                    'code' => 404,
                    'message' => 'This setting does not exist'
                ];
                return $this->json($callback, 404);
            }
            $setting->setValue($data['value']);
            $em = $this->doctrine->getManager();
            $em->persist($setting);
            $em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The setting is modified'
            ];
            return $this->json($callback, 200);
        }
    }
}