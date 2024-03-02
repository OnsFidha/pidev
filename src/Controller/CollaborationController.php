<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Collaboration;
use App\Form\CollaborationType;
use App\Repository\CollaborationRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailService;


#[Route('/collaboration')]
class CollaborationController extends AbstractController
{   
   
   
    #[Route('/mail/{id}', name: 'email')]
    public function contactUser(Request $request, MailService $mailService, $id,CollaborationRepository $collaborationRepository)
    {
        $mailService->sendEmail();
        // // Récupérer la collaboration en fonction de l'ID
        // $collaboration = $collaborationRepository->find($id);
    
        // if (!$collaboration) {
        //     throw $this->createNotFoundException('La collaboration avec l\'ID ' . $id . ' n\'a pas été trouvée.');
        // }
        // $users = $collaboration->getUser();
        // if ($users->isEmpty()) {
        //     throw $this->createNotFoundException('Aucun utilisateur associé à la collaboration avec l\'ID ' . $id);
        // }
        // $message = 'Contenu de l\'e-mail'; 
        // foreach ($users as $user) {
        //     $mailService->sendEmail($user->getEmail(), 'Sujet de l\'e-mail', $message);
        // }
    
         return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);

    }
    
    #[Route('/{id}', name: 'app_collaboration_index', methods: ['GET'])]
    public function index(CollaborationRepository $collaborationRepository,$id,PublicationRepository $publicationRepository): Response
    {
        $collaborations = $collaborationRepository->findByPublicationId($id);

        // Vous pouvez ensuite passer les collaborations à votre template pour les afficher, ou effectuer toute autre opération nécessaire
        return $this->render('collaboration/index.html.twig', [
            'collaborations' => $collaborations,
        ]);
      
    }

    #[Route('/new/{id}', name: 'app_collaboration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repU,PublicationRepository $repP, $id): Response
    {
        $collaboration = new Collaboration();
        $form = $this->createForm(CollaborationType::class, $collaboration);
        $form->handleRequest($request);
        $userId=1;
        $user = $repU->find($userId);
        $pub = $repP->find($id);
        if ($user !== null) {
        $collaboration->addUser($user);
        $collaboration->addPub( $pub);
        if ($form->isSubmitted() && $form->isValid()) {
            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $cvFileName = uniqid().'.'.$cvFile->guessExtension();
                $cvFile->move(
                    $this->getParameter('cv_directory'), 
                    $cvFileName 
                );
    
                // Mettre à jour le champ CV de l'entité Collaboration avec le nom du fichier téléchargé
                $collaboration->setCv($cvFileName);
            }
            $entityManager->persist($collaboration);
            $entityManager->flush();
            $this->addFlash('success', 'La collaboration a été ajoutée avec succès.');

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collaboration/new.html.twig', [
            'collaboration' => $collaboration,
            'form' => $form,
        ]);
    }
    }
    #[Route('/{id}', name: 'app_collaboration_show', methods: ['GET'])]
    public function show(Collaboration $collaboration): Response
    {
        return $this->render('collaboration/show.html.twig', [
            'collaboration' => $collaboration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collaboration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Collaboration $collaboration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CollaborationType::class, $collaboration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_collaboration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collaboration/edit.html.twig', [
            'collaboration' => $collaboration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collaboration_delete', methods: ['POST'])]
    public function delete(Request $request, Collaboration $collaboration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collaboration->getId(), $request->request->get('_token'))) {
            $entityManager->remove($collaboration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_collaboration_index', [], Response::HTTP_SEE_OTHER);
    }
}
