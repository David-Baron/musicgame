<?php 
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfileType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Controller\AdminController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AdminController
{   
    protected UserRepository $userRepository;
    protected EntityManagerInterface $em;
    
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }
    
    /**
     * @Route("/admin/users", name="admin_user_list", methods={"GET"})
     */
    public function list()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $this->userRepository->findAll();
        return $this->returnView('user-list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/users/user-create", name="admin_user_create", methods={"GET","POST"})
     */
    public function create(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordEncoder->hashPassword(
                $user,
                'RockAndRoll'
            ));
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->returnView('user-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/user/ajax-update", name="admin_user_ajax_update", methods={"GET","POST"})
     */
    public function ajaxUpdate(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $data = json_decode($request->getContent(), true);
        if ($data['id']) {
            $user = $this->userRepository->find($data['id']);
            if (!$user) {
                $callback = [
                    'success' => false,
                    'status' => 'Not Found',
                    'code' => 404,
                    'message' => 'This user does not exist'
                ];
                return $this->json($callback, 404);
            }
            $role = $data['role'];
            switch ($role) {
                case 'Administrateur':
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 'Moderateur':
                    $user->setRoles(['ROLE_MODO']);
                    break;
                case 'Utilisateur':
                    $user->setRoles(['ROLE_USER']);
                    break;
            }
            $this->em->persist($user);
            $this->em->flush();
            $callback = [
                'success' => true,
                'status' => 'Ok Request',
                'code' => 200,
                'message' => 'The user is modified'
            ];
            return $this->json($callback, 200);
        }
    }

    /**
     * Delete by admin
     * @Route("/admin/users/user/ajax-delete", name="admin_user_delete", methods={"POST"})
     */
    public function ajaxDelete(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // if app.user == request->getuser()
    }

    /**
     * Profile by user
     * @Route("/admin/my-account/my-profile", name="admin_user_user_profile", methods={"GET","POST"})
     */
    public function profileInAdmin(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_MODO');
        $user = $this->userRepository->loadUserByIdentifier($this->getUser()->getUserIdentifier());
        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $user = $profileForm->getData();
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('admin_user_user_profile');
        }

        $passwords = [];
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $passwords);
        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $passwords = $changePasswordForm->getData();
            if($passwordEncoder->hashPassword($user, $passwords['actualPassword']))
            {
                $user->setPassword($passwordEncoder->hashPassword(
                    $user,
                    $passwords['newPassword']
                ));
                $this->em->persist($user);
                $this->em->flush();
                return $this->redirectToRoute('app_logout');
            }
            // Else nothing append! actual password is wrong
        }
        
        return $this->returnView('user-profile.html.twig', [
            'profileForm' => $profileForm->createView(), 
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }

    /**
     * Delete by user
     * @Route("/mon-compte-{id}-delete", name="user_user_delete", methods="POST")
     */
    public function delete(Request $request)
    {
        // if app.user == request->getuser()
    }

}