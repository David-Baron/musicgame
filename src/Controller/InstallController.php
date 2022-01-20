<?php 
namespace App\Controller;

use App\Controller\AdminController;
use Symfony\Component\Routing\Annotation\Route;

class InstallController extends AdminController
{

    /**
     * @Route("/install", name="install", methods={"GET"})
     */
    public function install()
    {
        // Implement the process here

        // make storage dir
        // make database
        // make migrations
        // migrate migrations
        // create admin user
        // create settings
        // clear cache

        return $this->redirectToRoute('index');
    }
}