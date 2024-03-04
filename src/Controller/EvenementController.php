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
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;



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
    public function addEvent(ManagerRegistry $manager, Request $request,UserRepository $repU): Response
{
    $em = $manager->getManager();
    $userId = $this->getUser();
    $user = $repU->find($userId);
    $event = new Evenement();
    $event->setIdUser($user);
    $form = $this->createForm(EvenementType::class, $event);

    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
        $fileName = uniqid().'.'.$file->guessExtension();
        $file->move($this->getParameter('photo_dir'), $fileName);
        $event->setImage($fileName);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('app_list_eventt');
    }

    return $this->render('evenement/addevent.html.twig', ['form' => $form->createView()]);
}

    #[Route('/list/event', name: 'app_list_eventt')]
    public function listEvent(Request $request,EvenementRepository $eventrepo,PaginatorInterface $paginator): Response
    {
        $events= $eventrepo->findAll();
        $events = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        return $this->render('evenement/listevent.html.twig', [
            'events' => $events,
        ]);
    }
    #[Route('/editevent/{id}', name: 'editevent')]
    public function editEvenement(Request $request, ManagerRegistry $manager, $id, EvenementRepository $eventrepository): Response
    {
        $em = $manager->getManager();
    
        $event  = $eventrepository->find($id);
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_list_eventt');
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
    
            return $this->redirectToRoute('app_list_eventt');
        }
        #[Route('/event/{id}',name:'event_details')]
        public function detail($id,EvenementRepository $eventrep,FeedbackRepository $fbRepository,PaginatorInterface $paginator, Request $request  ): Response 
        {
            $fb= $fbRepository->findBy(['id_evenement' => $id]);
            $event = $eventrep->find($id);
           
        $fb = $paginator->paginate(
            $fb, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
            return $this->render(
                'evenement/showevent.html.twig',
                ['event' => $event,
                'fb'=>$fb]
    
            );
            
        }
        #[Route('/listadmin', name: 'list_admin')]
    public function listEventA(EvenementRepository $eventrepo): Response
    {
        return $this->render('admin/eventadmin.html.twig', [
            'events' => $eventrepo->findAll(),
        ]);
    }
    #[Route('/calendrier', name: 'app_evenement')]
    public function calendar(EvenementRepository $calendar): Response
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDateDebut()->format('Y-m-d'),
                'end' => $event->getDateFin()->format('Y-m-d'),
                'title' => $event->getNom(),
                'description' => $event->getDescription(),
                'backgroundColor' => '#fcb97e',
                'borderColor' => '#fba22e',
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('evenement/calendrier.html.twig', compact('data'));
    }
    // #[Route('/email',name: 'email')]
    // public function Participer(MailerInterface $mailer): Response
    // {
    //     $email = (new Email())
    //         ->from('syrine.zaier@esprit.tn')
    //         ->to('Syrinezaier283@gmail.com')
    //         ->subject('Invitation')
    //         ->text('Vous etes invité à ....');
          

    //     $mailer->send($email);
    //     return $this->redirectToRoute('app_list_eventt');

       
    // }
    
}
