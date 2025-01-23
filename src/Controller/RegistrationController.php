<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\SessionManagerService;
use App\Service\UserInvitationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, SessionManagerService $sessionManagerService, UserPasswordHasherInterface $userPasswordHasher, FormFactoryInterface $formFactory, Security $security, EntityManagerInterface $entityManager, UserInvitationManager $userInvitationManager): Response
    {

        if ($this->getUser()) {

            return $this->redirectToRoute('app_user_dashboard');
        }

        $addRolesField = true;
        $isRecruiterRegistration = false;
        if ($request->query->get('invitation')) {
            $sessionManagerService->createSession('invitationIsAccepted', true);
            $addRolesField = false;
            $isRecruiterRegistration = true;
        }


        $user = new User();
        $form = $formFactory->create(RegistrationFormType::class, $user, ['add_roles_field' => $addRolesField]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($isRecruiterRegistration && !$addRolesField) {
                $user->setRoles(['ROLE_RECRUITER']);
            } else {

                $roleFromRegistration = $form->get('youAre')->getData();

                if ($roleFromRegistration === 'candidate') {
                    $user->setRoles(['ROLE_CANDIDATE']);
                } elseif ($roleFromRegistration === "company") {

                    $user->setRoles(["ROLE_MANAGER"]);
                }
            }

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));


            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // return $security->login($user, 'form_login', 'main');
            if ($sessionManagerService->getInvitationIsAccepted('invitationIsAccepted')) {
                $invitationId = $request->query->get('invitation');
                $userInvitationManager->updateInvitationFromRegister($entityManager, $invitationId, $user);
            }


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
