<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $message='';
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            $prix = $form->get('prix')->getData();
            $qte = $form->get('quantite')->getData();

           // echo $prix;
           // echo  $qte ;
         if($prix <0)
         {
            $message='Le prix est invalide';
         }
         else if($qte <=0)
         {
          $message='La quantité est invalide';
         } else if(! $imageFile){
            $message='Il faut choisir une image';
         }
         else{
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('photo_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImage($newFilename);

            }


            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
    }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/{id}/detail', name: 'detail_image', methods: ['GET'])]
    public function detail_image(Produit $produit): Response
    {
        return $this->render('produit/detail_image.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(ProduitRepository $produitRepository,Request $request, Produit $produit, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $id=$produit->getId();
       
        $p=$produitRepository->findOneBySomeField($id);
        $aimage= $p->getImage();
          
        $message='';
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
             
            $prix = $form->get('prix')->getData();
            $qte = $form->get('quantite')->getData();

            if($prix <0)
            {
               $message='Le prix est invalide';
            }
            else if($qte <=0)
            {
             $message='La quantité est invalide';
            }
         else if (! $imageFile) {
            
            $produit->setImage($aimage);

            }else {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('photo_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
               

               $produit->setImage($newFilename);
               
            }



            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

         
        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'message' => $message,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['GET','POST'])]
    public function delete(ProduitRepository $produitRepository,Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
          $message='';
          $id=$produit->getId();
          $p=$produitRepository->findOneBySomeField($id);
       
            $entityManager->remove($p);
            $entityManager->flush();
            $message='Suppression avec succées';
     

        return $this->render('produit/index.html.twig',
        ['message' => $message,
        'produits' => $produitRepository->findAll()]);
    }
}
