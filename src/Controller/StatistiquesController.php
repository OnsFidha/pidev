<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;

class StatistiquesController extends AbstractController
{
    // #[Route('/statistiques', name: 'app_statistiques')]
    // public function index(): Response
    // {
    //     return $this->render('statistiques/index.html.twig', [
    //         'controller_name' => 'StatistiquesController',
    //     ]);
    // }



    #[Route('/statistiques', name:'statistiques')]
     
    public function index(ReclamationRepository $reclamationRepository, ReponseRepository $reponseRepository): Response
    {
        // Récupérer le nombre total de réclamations
        $nombreTotalReclamations = $reclamationRepository->count([]);

        // Récupérer le nombre de réclamations ayant une réponse
        $nombreReclamationsAvecReponse = $reponseRepository->countReclamationsWithResponse();

        // Calculer le pourcentage
        $pourcentageReclamationsAvecReponse = $nombreTotalReclamations > 0 ? ($nombreReclamationsAvecReponse / $nombreTotalReclamations) * 100 : 0;

        return $this->render('statistiques/index.html.twig', [
            'pourcentage_reclamations_avec_reponse' => $pourcentageReclamationsAvecReponse,
        ]);
    }



     
     
     #[Route('/statistiques/histogramme', name:'histogramme_reclamations_par_type')]
     
    public function histogrammeReclamationsParType(ReclamationRepository $reclamationRepository): Response
    {
         $types = $reclamationRepository->findTypes();
    $counts = $reclamationRepository->countByType();
    
    // Calculate total number of reclamations
    $totalReclamations = array_reduce($counts, function ($carry, $item) {
        return $carry + $item['count'];
    }, 0);

    // Render the Twig template with the retrieved data
    return $this->render('statistiques/index2.html.twig', [
        'types' => $types,
        'counts' => $counts,
        'totalReclamations' => $totalReclamations,
    ]);
    }
}
