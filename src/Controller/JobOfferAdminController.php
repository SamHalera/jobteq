<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[Route('/jobteq/admin')]
#[IsGranted('ROLE_RECRUITER')]
class JobOfferAdminController extends AbstractController
{
    #[Route('/', name: 'app_job_offer_admin_index')]
    public function index(JobOfferRepository $jobRepo): Response
    {
        $jobOffers = $jobRepo->findAll();
        return $this->render('job_offer_admin/index.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }
    #[Route('/new', name: 'app_joboffer_create', methods: ['POST', 'GET'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // /**
            //  * @var UploadedFile $file
            //  */

            // $file = $form->get('thumbnailFile')->getData();
            // $extension = $file->getClientOriginalExtension();
            // $fileName =  bin2hex(random_bytes(4)) . '_' . uniqid() . '.' . $extension;

            // $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/job-offers';
            // $file->move($publicFolder, $fileName);
            // $jobOffer->setThumbnail($fileName);

            $em->persist($jobOffer);
            $em->flush();

            $this->addFlash('success', 'A new job offer has been created!');
            return $this->redirectToRoute('app_job_offer_admin_index');
        }

        return $this->render('job_offer_admin/new.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/joboffer/{slug}', name: 'app_joboffer_edit', methods: ['POST', 'GET'])]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        #[MapEntity(mapping: ['slug' => 'slug'])]
        JobOffer $jobOffer
    ): Response {

        $form = $this->createForm(JobOfferType::class, $jobOffer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($jobOffer);
            $em->flush();
            $this->addFlash('success', 'Job offer has been updated!');

            return $this->redirectToRoute('app_job_offer_admin_index');
        }

        return $this->render('job_offer_admin/edit.html.twig', [
            'form' => $form,
            'jobOffer' => $jobOffer
        ]);
    }
    #[Route('/joboffer/delete/{slug}', name: 'app_joboffer_delete')]
    public function delete(
        EntityManagerInterface $em,
        #[MapEntity(mapping: ['slug' => 'slug'])]
        JobOffer $jobOffer
    ): Response {

        // dd("hello inside controller");
        if ($jobOffer->getStatusString() === "published") {

            $this->addFlash('danger', 'Job offer cannot be deleted because is published!');
            return $this->redirectToRoute('app_job_offer_admin_index');
        }
        $em->remove($jobOffer);
        $em->flush();

        $this->addFlash('success', 'Job offer has been deleted!');
        return $this->redirectToRoute('app_job_offer_admin_index');
    }
}
