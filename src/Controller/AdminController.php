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
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ParticipationRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(FeedbackRepository $fbRepository, EvenementRepository $eventRepository): Response
    {
        return $this->render('admin/eventadmin.html.twig', [
            
            'feedback' => $fbRepository->findAll(),
            'event' => $eventRepository->findAll(),
            
        ]);
    }
    #[Route('/newfbadmin/{id}', name: 'admin_feedback_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_admin');
        }

        return $this->renderForm('admin/newfeedback.html.twig', [
            'feedback' => $feedback,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/editadmin', name: 'admin_feedback_edit', methods: ['GET', 'POST'])]
    public function editFeedback(Request $request, ManagerRegistry $manager, $id, FeedbackRepository $fbrepository): Response
    {
        $em = $manager->getManager();
    
       
        $fb  = $fbrepository->find($id);
        $form = $this->createForm(FeedbackType::class, $fb);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($fb);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }
    
        return $this->renderForm('admin/editfeedback.html.twig', [
            'feedback' => $fb,
           
            'form' => $form,
        ]);
    }
    #[Route('/admindelete/{id}', name: 'admin_feedback_delete')]
    
    public function delete(Request $request, $id, ManagerRegistry $manager, FeedbackRepository $fbRepository): Response
    {
            $em = $manager->getManager();
            $fb= $fbRepository->find($id);
    
            $em->remove($fb);
            $em->flush();
    
            return $this->redirectToRoute('app_admin');
    }
    
    #[Route('/addadminevenement', name: 'admin_evenement')]
    public function addEvent(ManagerRegistry $manager, Request $request): Response
   {
    $em = $manager->getManager();

    $event = new Evenement();

    $form = $this->createForm(EvenementType::class, $event);

    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
        $fileName = uniqid().'.'.$file->guessExtension();
        $file->move($this->getParameter('photo_dir'), $fileName);
        $event->setImage($fileName);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('app_admin');
    }

    return $this->render('admin/addevent.html.twig', ['form' => $form->createView()]);
   }

   
    #[Route('/editadminevent/{id}', name: 'admineditevent')]
    public function editEvenement(Request $request, ManagerRegistry $manager, $id, EvenementRepository $eventrepository): Response
    {
        $em = $manager->getManager();
    
        $event  = $eventrepository->find($id);
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }
    
        return $this->renderForm('admin/editevent.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    #[Route('/admindeleteevent/{id}', name: 'admindeleteevent')]
        public function deleteevenement(Request $request, $id, ManagerRegistry $manager, EvenementRepository $eventRepository): Response
        {
            $em = $manager->getManager();
            $event= $eventRepository->find($id);
    
            $em->remove($event);
            $em->flush();
    
            return $this->redirectToRoute('app_admin');
        }

        #[Route('/stats', name: 'stats')]
        public function statistiques(EvenementRepository $eventRepo){
            // On va chercher toutes les catégories
             $events = $eventRepo->findAll();
    
             $eventNom = [];
             $eventpartCount = [];
             $eventCount = [];

    
            // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
             foreach($events as $event){
                 $eventNom[] = $event->getNom();
            //     $categColor[] = $categorie->getColor();
                 $eventCount[] = count($event->getFeedback());
                 $eventpartCount[] = count($event->getParticipations());
             }
    
            
    
            return $this->render('admin/stats.html.twig', [
                'eventNom' => json_encode($eventNom),
                
                'eventCount' => json_encode($eventCount),
                'eventpartCount' => json_encode($eventpartCount),
               
            ]);
        }
    

}
