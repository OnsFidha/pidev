<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\EvenementRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;



class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
    #[Route('/addevenement', name: 'add_evenement')]
    // public function addEvent(ManagerRegistry $manager, Request $request): Response
    // {
    //     $em = $manager->getManager();

    //     $event = new Evenement();



    //     $form = $this->createForm(EvenementType::class, $event);


    //     $form->handleRequest($request);
       
    //     if ($form->isSubmitted() && $form->isValid()) {


    //         $em->persist($event);
    //         $em->flush();

    //         return $this->redirectToRoute('list_event');
    //     }

    //     return $this->renderForm('evenement/addevent.html.twig', ['form' => $form->createView()]);

        
    // }
    
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

        return $this->redirectToRoute('list_event');
    }

    return $this->render('evenement/addevent.html.twig', ['form' => $form->createView()]);
}

    #[Route('/listEvent', name: 'list_event')]
    public function listEvent(EvenementRepository $eventrepo): Response
    {
        return $this->render('evenement/listevent.html.twig', [
            'events' => $eventrepo->findAll(),
        ]);
    }
    #[Route('/editevent/{id}', name: 'editevent')]
    public function editEvenement(Request $request, ManagerRegistry $manager, $id, EvenementRepository $eventrepository): Response
    {
        $em = $manager->getManager();
    
        $event  = $eventrepository->find($id);
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('list_event');
        }
    
        return $this->renderForm('evenement/editevent.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    #[Route('/deleteevent/{id}', name: 'deleteevent')]
        public function deleteevenement(Request $request, $id, ManagerRegistry $manager, EvenementRepository $eventRepository): Response
        {
            $em = $manager->getManager();
            $event= $eventRepository->find($id);
    
            $em->remove($event);
            $em->flush();
    
            return $this->redirectToRoute('list_event');
        }
        #[Route('/event/{id}',name:'event_details')]
        public function detail($id,EvenementRepository $eventrep): Response 
        {
            $event = $eventrep->find($id);
            return $this->render(
                'evenement/showevent.html.twig',
                ['event' => $event,]
    
            );
            
        }
        #[Route('/listadmin', name: 'list_admin')]
    public function listEventA(EvenementRepository $eventrepo): Response
    {
        return $this->render('admin/eventadmin.html.twig', [
            'events' => $eventrepo->findAll(),
        ]);
    }
    
}
