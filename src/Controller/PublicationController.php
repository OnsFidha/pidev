<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Publication;
use App\Entity\Commentaire;
use App\Form\PublicationType;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use App\Repository\CollaborationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->trie_decroissant_date(),
        ]);
    }
    //chart 
    #[Route('/admin', name: 'app_publication_admin', methods: ['GET'])]
    public function index2(PublicationRepository $publicationRepository): Response
    {
        $offerCount = $publicationRepository->countByType('offre');
        $ordinaryCount = $publicationRepository->countByType('ordinaire');
        $publications = $publicationRepository->findAll();
        $publicationLabels = [];
        $collaboratorCounts = [];
        foreach ($publications as $publication) {
            $publicationLabels[] = 'nbre collaborateurs pour pub #'.$publication->getId(); 
            $collaboratorCounts[] = count($publication->getCollaborations()); 
        }
        return $this->render('publication/admin_pub.html.twig', [
            'publications' => $publicationRepository->findAll(),
            'offerCount' => $offerCount,
            'ordinaryCount' => $ordinaryCount,
            'publicationLabels' => $publicationLabels,
            'collaboratorCounts' => $collaboratorCounts,
        ]);
    }
    
    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repU): Response
    {    $userId=1;
        $user = $repU->find($userId);
        $publication = new Publication();
        $publication->setIdUser($user);
        $publication->setDateCreation(new \DateTime()); 
        $form = $this->createForm(PublicationType::class, $publication, ['attr' => ['enctype' => 'multipart/form-data']] );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photo')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
            $publication->setPhoto($fileName);
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_publication_show', methods: ['GET', 'POST'])]
    public function show(Publication $publication, CommentaireRepository $commentaireRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaires = $commentaireRepository->findBy(['id_publication' => $publication]);
        $commentaire = new Commentaire();
        $commentaire->setDateCreation(new \DateTime());
        $commentForm = $this->createForm(CommentaireType::class, $commentaire);
        $commentForm->handleRequest($request);
    
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentaire->setIdPublication($publication);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()]);
        }
    
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'commentaires' => $commentaires,
            'commentForm' => $commentForm->createView(),
        ]);
    }
    #[Route('/com/{id}', name: 'app_publication_showCom')]
    public function showc(Publication $publication, CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findBy(['id_publication' => $publication]);
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires
        ]);}
    


    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {   $photoold=$publication->getPhoto();   
        $path=$this->getParameter('images_directory').'/'.$photoold; 
        
        $publication->setPhoto($path);
        $form = $this->createForm(PublicationType::class, $publication, [
            'attr' => ['enctype' => 'multipart/form-data'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($publication->getPhoto()!=null){
                $file = $form->get('photo')->getData();
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('images_directory'), $fileName);
                $publication->setPhoto($fileName);}
                else{
                $publication->setPhoto($photoold);}
                $publication->setDateModification(new \DateTime());
                $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm ('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    
     #[Route('/get/{id}', name: 'app_publication_showAdd', methods: ['GET'])]
    public function showAd(Publication $publication,CommentaireRepository $commentaireRepository): Response
    {
        $commentaires= $commentaireRepository->findBy(['id_publication' => $publication]);
        return $this->render('publication/showAd.html.twig', [
            'publication' => $publication
        ]);
    }
    #[Route('/de/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
  
    #[Route("/filter-publications", name: "filter-publications", methods: ["POST"])]
    public function filterPublications(Request $request, PublicationRepository $publicationRepository)
    {
        $type = $request->request->get('publicationType');
            if ($type === 'offre' || $type === 'ordinaire') {
                $publications = $publicationRepository->findByType($type);
            } else {
                $publications = $publicationRepository->findAll();
            }

            $responseData = [];
            foreach ($publications as $publication) {
                $responseData[] = [
                    'id' => $publication->getId(),
                    'photo' => $publication->getPhoto(),
                    'type' => $publication->getType(),
                    'lieu' => $publication->getLieu(),
                    'text' => $publication->getText(),
                    'dateCreation' => $publication->getDateCreation()->format('Y-m-d'), 
                    'dateModification' => $publication->getDateModification()->format('Y-m-d'), 
                ];
            }

            return new JsonResponse(['publications' => $responseData]);
       
    }
    
    #[Route('/d/{id}', name: 'app_publication_deleteA', methods: ['POST'])]
    public function deleteA(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_admin', [], Response::HTTP_SEE_OTHER);
    }
}
