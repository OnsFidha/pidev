<?php

namespace App\Controller;

use App\Entity\DetailCommande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;




class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_add_commande')]
    public function add(UserRepository $userRepository,SessionInterface $session,ProduitRepository $produitRepository,EntityManagerInterface $entityManager): Response
    {
        $user=$userRepository->findOneBySomeField();
        //dd($user);
        $panier=$session->get('panier',[]);
        //dd($panier);
        if($panier===[]){
            $this->addFlash('notice','votre panier est vide');
            return $this->redirectToRoute('app_shopping');
        }else{
            $commande=new Commande();
            $commande->setUser($user);
            $commande->setReference('');
            $commande->setDateCommande(new \DateTime('now'));
        //boucler le panier
        //parcourt chaque élément du tableau $panier et stocke la clé de l'élément dans la variable $item et la valeur de l'élément dans la variable $quantite
        foreach($panier as $item=>$quantite){
            $DetailCommande=new DetailCommande();
            $produit=$produitRepository->find($item);
            $DetailCommande->setProduit($produit);
            $DetailCommande->setQuantite($quantite);
            $DetailCommande->setPrix($produit->getPrix());
            $commande->addDetailCommande($DetailCommande);
        }
        //persist est utilisé pour sauvegarder des données en mémoire, flush pour les enregistrer dans la base de données
         $entityManager->persist($commande);
         $entityManager->flush();
         $this->addFlash('notice','Votre commande est bien passer');

        }
         $session->remove('panier');
        return $this->redirectToRoute('app_shopping');
    }
}
