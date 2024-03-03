<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/m', name: 'app_homepage')]
    public function index(): Response
    {
        //return $this->render('indexp.html.twig', ['user' => $this->getUser()]);
        return $this->render('index.html.twig', ['user' => $this->getUser()]);
    }
}