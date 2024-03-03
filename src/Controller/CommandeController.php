<?php

namespace App\Controller;

use App\Entity\DetailCommande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;

use App\Repository\DetailCommandeRepository;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;




class CommandeController extends AbstractController
{
    #[Route('/commande/ajouter', name: 'app_commande_add')]
    public function add_commande(ProduitRepository $produitRepository,SessionInterface $session, EntityManagerInterface $entityManager,UserRepository $userrepository): Response
    {
      $id='1';
        // $this->$this->denyAccessUnlessGranted('ROLE_USER');
         $user=$userrepository->findOneBySomeField($id);


       $panier =$session->get('panier',[]);

      if($panier === [])
      {
        $this->addFlash('message','Votre panier est vide');
         return $this->redirectToRoute('app_shopping');
      }else{

          $commande = new Commande();
          $commande->setUser($user);
          $commande->setReference('2024');
          $commande->setDateCommande( new \DateTime());
          $commande->setMontantTotal($session->get('total'));

          foreach($panier as $item =>$quantite){
            $DetailCommande = new DetailCommande();
            $produit =$produitRepository->find($item);
            $DetailCommande->setProduit($produit);
            $DetailCommande->setQuantite($quantite);
            $DetailCommande->setPrix($produit->getPrix());
            
            $commande->addDetailCommande($DetailCommande);
            
             }

             $entityManager->persist($commande);
             $entityManager->flush();

             $session->remove('panier'); 
      }
      $session->set('total',0);
      $session->set('nb',0);


        return $this->render('shopping/index.html.twig', [
          'produits' => $produitRepository->findAll(),
        ]);
    }
    #[Route('/commande', name: 'app_commande')]
    public function index(CommandeRepository $commandeRepository,SessionInterface $session, EntityManagerInterface $entityManager,UserRepository $userrepository): Response
    {
      return $this->render('commande/index.html.twig', [
        'commandes' => $commandeRepository->findAll(),
    ]);
    }

    #[Route('/detail_commande/{id}', name: 'app_commande_detail')]
    public function detail_commande(Commande $commande,DetailCommandeRepository $detailCommandeRepository,SessionInterface $session, EntityManagerInterface $entityManager,CommandeRepository $commandeRepository): Response
    {
      
      $id=$commande->getId();
        
         
        $detailCommande =$commande->getDetailCommandes();
        
   
        return $this->render('commande/detail.html.twig', [
          'commandes' => $commande ,
          'detailCommande' => $detailCommande ,
        ]);
    }


}
