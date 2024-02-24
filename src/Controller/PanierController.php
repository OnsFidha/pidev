<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Repository\ProduitRepository;


class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier',methods: ['GET', 'POST'])]
    public function index (SessionInterface $session,ProduitRepository $produitRepository): Response
    {
        //on récupère le panier existante   
        $panier=$session->get('panier',[]);
        $data =[];
        $total=0;
        foreach($panier as $id =>$quantite){
            $produit =$produitRepository->find($id);
            $data[]=[
                'produit'=>$produit,
                'quantite'=>$quantite
            ];
            $total+=$produit->getPrix()*$quantite;
        }
        

        
            
        
        return $this->render('panier/index.html.twig',compact('data','total'));
    }
    #[Route('/panier/{id}', name: 'app_add_panier',methods: ['GET', 'POST'])]
    public function add(Produit $produit,SessionInterface $session): Response
    {
        //on récupère l'id
        $id=$produit->getId();

        //on récupère panier
        $panier=$session->get('panier',[]);
        if(empty($panier[$id])){
            $panier[$id]=1;
        }else{
            $panier[$id]++;

            }
            $session->set('panier',$panier);

            
        
        return $this->redirectToRoute('app_panier');
    }
    #[Route('remove/panier/{id}', name: 'app_remove_panier',methods: ['GET', 'POST'])]
    public function remove(Produit $produit,SessionInterface $session): Response
    {
        //on récupère l'id
        $id=$produit->getId();

        //on récupère panier
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id]))
        {
            if($panier[$id] >1)
            {
                $panier[$id]--;
            }
            else
            {
                unset($panier[$id]);
            }
           
          

        } 
        $session->set('panier',$panier);
        return $this->redirectToRoute('app_panier');
    }
    #[Route('delete/panier/{id}', name: 'app_delete_panier',methods: ['GET', 'POST'])]
    public function delete(Produit $produit,SessionInterface $session): Response
    {
        //on récupère l'id
        $id=$produit->getId();

        //on récupère panier
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id]))
        {
            
            
                unset($panier[$id]);
            
           
           

        } 
        $session->set('panier',$panier);
        return $this->redirectToRoute('app_panier');
    }
    #[Route('empty/panier', name: 'app_empty_panier',methods: ['GET', 'POST'])]
    public function empty(SessionInterface $session): Response
    {
        
       $session->remove('panier');
        return $this->redirectToRoute('app_panier');
    }
}
