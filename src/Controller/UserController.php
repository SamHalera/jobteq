<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]

class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function index(): Response
    {

        // $user->setRoles(["ROLE_RECRUITER"]);

        return $this->render('user/dashboard.html.twig');
    }
    #[Route('/user/add-candidate-role/{id}', name: 'app_user_candidate_register')]
    public function addCandidateRole(User $user, EntityManagerInterface $em): Response
    {


        if (!$user) {
            $this->createNotFoundException();
        }
        $user->setRoles(["ROLE_CANDIDATE"]);

        // $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_login');
    }
}
