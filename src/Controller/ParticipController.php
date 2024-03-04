<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
//use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;


//#[Route('/particip')]
class ParticipController extends AbstractController
{
    #[Route('particip/p', name: 'app_particip_index', methods: ['GET'])]
    public function index(ParticipationRepository $participationRepository): Response
    {
        return $this->render('particip/index.html.twig', [
            'participations' => $participationRepository->findAll(),
        ]);
    }

    #[Route('particip/newp/{id}', name: 'app_particip_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $participation = new Participation();
    //     $form = $this->createForm(ParticipationType::class, $participation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($participation);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_particip_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('particip/new.html.twig', [
    //         'participation' => $participation,
    //         'form' => $form,
    //     ]);
    // }
//     
public function new(ManagerRegistry $manager, Request $request, $id, EvenementRepository $eventRepository, ParticipationRepository $participationRepository, UserRepository $userRepository,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();

        // Assuming you have an authenticated user
        // $user = $this->getUser();
         $user = $userRepository->find($id);

        // Get the event based on the provided $id
        $event = $eventRepository->find($id);
        

        // Check if the event exists
        // if (!$event) {
        //     // Handle the case where the event is not found, e.g., redirect or show an error message
        //     // Replace this line with the appropriate response for your application
        //     return new Response('Event not found', Response::HTTP_NOT_FOUND);
        // }

        // Check if the user has already participated in this event
        // $existingParticipation = $participationRepository->findOneBy([
        //     'Idevent' => $event,
        //     'IdUser' => 1,
        // ]);

        // if ($existingParticipation) {
        //     // Handle the case where the user has already participated in this event
        //     // Replace this line with the appropriate response for your application
        //     return new Response('You have already participated in this event', Response::HTTP_CONFLICT);
        // }

        // Create a new Participation entity
        $participation = new Participation();
        $participation->addIdevent($event);
        $participation->addIdUser($user);

        $form = $this->createForm(ParticipationType::class, $participation);

        $form->handleRequest($request);

       
            // Assuming you have a property $nbreParticipants in your Event entity
            if ($event->getNbreParticipants() >= $event->getNbreMax()) {
                // Handle the case where the participation limit is reached
                // Return a JSON response with the error message
                return new JsonResponse(['error' => 'Participation limit reached'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Increase the number of participants
            $event->setNbreParticipants($event->getNbreParticipants() + 1);
            $cheminImage = 'uploads/images/' . $event->getImage();
            $nomEvenement = $event->getNom();
            $dateDebut = $event->getDateDebut()->format('d F Y');

            // Persist and flush both entities
            $em->persist($event);
            $em->persist($participation);
            $em->flush();
            $email = (new Email())
            ->from('syrine.zaier@esprit.tn')
            ->to('Syrinezaier283@gmail.com')
            ->subject('Invitation')
            ->text("Vous êtes invité à l'événement '$nomEvenement' qui aura lieu le $dateDebut.")
            ->attachFromPath($cheminImage, $event->getImage(), 'image/jpeg');
      

            $mailer->send($email);

            return $this->redirectToRoute('app_list_eventt');
        
           

        return $this->renderForm('particip/new.html.twig', [
            'participation' => $participation,
            'event' => $event,
            'form' => $form,
        ]);
    }
    

    #[Route('particip/p/{id}', name: 'app_particip_show', methods: ['GET'])]
    // public function show(Participation $participation): Response
    // {
    //     return $this->render('particip/show.html.twig', [
    //         'participation' => $participation,
    //     ]);
    // }
    public function show($id,ParticipationRepository $prrep): Response 
    {
            $pr = $prrep->find($id);
            return $this->render(
                'particip/show.html.twig',
                ['participation' => $pr,]
    
            );
            
    }

    #[Route('particip/p/{id}/edit', name: 'app_particip_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ParticipationType::class, $participation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_particip_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('particip/edit.html.twig', [
    //         'participation' => $participation,
    //         'form' => $form,
    //     ]);
    // }
    public function edit(Request $request, ManagerRegistry $manager, $id, ParticipationRepository $prrepository): Response
    {
        $em = $manager->getManager();
    
       
        $pr  = $prrepository->find($id);
        $form = $this->createForm(ParticipationType::class, $pr);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($pr);
            $em->flush();
            return $this->redirectToRoute('app_particip_index');
        }
    
        return $this->renderForm('particip/edit.html.twig', [
            'part' => $pr,
           
            'form' => $form,
        ]);
    }

    #[Route('particip/p/{id}', name: 'app_particip_delete')]
    // public function delete(Request $request, Participation $participation, ManagerRegistry $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($participation);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_particip_index', [], Response::HTTP_SEE_OTHER);
    // }
    public function delete(Request $request, $id, ManagerRegistry $manager, ParticipationRepository $parRepository): Response
        {
            $em = $manager->getManager();
            $par= $parRepository->find($id);
    
            $em->remove($par);
            $em->flush();
    
            return $this->redirectToRoute('app_particip_index', []);
        }
       

}
