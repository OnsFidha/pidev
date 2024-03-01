<?php

namespace App\Controller;

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
}
