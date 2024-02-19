<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }
    #[Route('/admin', name: 'app_publication_admin', methods: ['GET'])]
    public function index2(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/admin_pub.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
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

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication,CommentaireRepository $commentaireRepository): Response
    {
        $commentaires= $commentaireRepository->findBy(['id_publication' => $publication]);
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'commentaires'=>$commentaires
        ]);
    }

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

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
