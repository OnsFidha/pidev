<?php

namespace App\Controller;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
 
 

class ShoppingController extends AbstractController
{
    #[Route('/shopping', name: 'app_shopping')]
    public function index(ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('shopping/index.html.twig', [
            'produits' => $produitRepository->findByExampleField(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }
    
}
