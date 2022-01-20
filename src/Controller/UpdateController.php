<?php 
namespace App\Controller;

use App\Controller\AdminController;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AdminController
{

    /**
     * @Route("/admin/update", name="admin_app_update", methods={"GET"})
     */
    public function update()
    {
        // Implement the process here

        // make backup
        // download package
        // unzip package
        // replace files
        // make migrations
        // migrate migrations
        // clear cache
        
        return $this->redirectToRoute('admin_about');
    }
}