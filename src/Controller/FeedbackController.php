<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FeedbackRepository;
use App\Entity\Feedback;
use App\Form\FeedbackType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Evenement;

class FeedbackController extends AbstractController
{
    #[Route('/feedback', name: 'app_feedback')]
    public function index(FeedbackRepository $fbRepository): Response
    {
        return $this->render('feedback/index.html.twig', [
            'feedback' => $fbRepository->findAll(),
        ]);
    }
    #[Route('/new/{id}', name: 'app_feedback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $feedback = new Feedback();
        $event = $entityManager->getRepository(Evenement::class)->find($id);
        $feedback->setIdEvenement($event);
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($feedback);
            $entityManager->flush();

            return $this->redirectToRoute('event_details', ['id' => $feedback->getIdEvenement()->getId()]);
        }

        return $this->renderForm('feedback/new.html.twig', [
            'feedback' => $feedback,
            'form' => $form,
        ]);
    }

    #[Route('/feedback/{id}', name: 'app_feedback_show')]
    // public function show(Feedback $feedback): Response
    // {
    //     return $this->render('feedback/show.html.twig', [
    //         'feedback' => $feedback,
    //     ]);
    // }
    public function show($id,FeedbackRepository $fbrep): Response 
    {
            $fb = $fbrep->find($id);
            return $this->render(
                'feedback/show.html.twig',
                ['feedback' => $fb,]
    
            );
            
    }

    #[Route('/{id}/edit', name: 'app_feedback_edit', methods: ['GET', 'POST'])]
    public function editFeedback(Request $request, ManagerRegistry $manager, $id, FeedbackRepository $fbrepository): Response
    {
        $em = $manager->getManager();
    
       
        $fb  = $fbrepository->find($id);
        $form = $this->createForm(FeedbackType::class, $fb);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($fb);
            $em->flush();
            return $this->redirectToRoute('event_details', ['id' => $fb->getIdEvenement()->getId()]);
        }
    
        return $this->renderForm('feedback/edit.html.twig', [
            'feedback' => $fb,
           
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_feedback_delete')]
    // public function delete(Request $request, Feedback $feedback, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$feedback->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($feedback);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_feedback_index', [], Response::HTTP_SEE_OTHER);
    // }
    public function delete(Request $request, $id, ManagerRegistry $manager, FeedbackRepository $fbRepository): Response
        {
            $em = $manager->getManager();
            $fb= $fbRepository->find($id);
    
            $em->remove($fb);
            $em->flush();
    
            return $this->redirectToRoute('event_details', ['id' => $fb->getIdEvenement()->getId()]);
        }
}
