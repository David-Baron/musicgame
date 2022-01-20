<?php 
namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Controller\FrontController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends FrontController
{
    /**
     * @Route("/registration", name="user_registration", methods={"GET", "POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('app_login');
        }

        return $this->returnView('user-registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}