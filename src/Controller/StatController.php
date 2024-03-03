<?php

namespace App\Controller;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'stat')]
    public function index(UserRepository $userRepository): Response
    {
        $totalUsers = $userRepository->getTotalUsersCount();

        $rolesCount = $userRepository->countUsersByRole();
        $verifiedUsers = $userRepository->getVerifiedUsersCount();

        return $this->render('stat/stat.html.twig', [
            'rolesCount' => $rolesCount,  'totalUsers' => $totalUsers,'verifiedUsers' => $verifiedUsers, // Passez le nombre total d'utilisateurs à la vue Twig
        ]);
    }
    #[Route('/statPub', name: 'statPub')]
    public function statPub(PublicationRepository $publicationRepository): Response
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
        return $this->render('publication/pubStat.html.twig', [
            'offerCount' => $offerCount,
            'ordinaryCount' => $ordinaryCount,
            'publicationLabels' => $publicationLabels,
            'collaboratorCounts' => $collaboratorCounts,
        ]);
    }
    
}
