<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    
// 
//     #[Route('/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
//     public function new(Request $request, EntityManagerInterface $entityManager): Response
//     {
//         $reponse = new Reponse();
//         $form = $this->createForm(ReponseType::class, $reponse);
//         $form->handleRequest($request);
// 
//         if ($form->isSubmitted() && $form->isValid()) {
//             $entityManager->persist($reponse);
//             $entityManager->flush();
// 
//             return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
//         }
// 
//         return $this->renderForm('reponse/new.html.twig', [
//             'reponse' => $reponse,
//             'form' => $form,
//         ]);
//     }



// ::

/**
     * @Route("/new/{id}", name="app_reponse_new", methods={"GET", "POST"})
     */
    #[Route('/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reclamation=new Reclamation();
        $reclamation=$entityManager->getRepository(Reclamation::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat(true);
            $reponse->setRelation($reclamation);
            $reponse->setDateReponse(new \DateTime('now'));
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index2', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),

        ]);
    }

// ::
    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

//

   
//     #[Route('/{id}/response', name: 'app_reclamation_response_show', methods: ['GET'])]
//     public function showReclamationResponse(Reclamation $reclamation, ReponseRepository $responseRepository): Response
//     {
//         // Retrieve the response associated with the given reclamation
//         $response = $responseRepository->findOneBy(['relation' => $reclamation]);
// 
//         // Check if a response is found
//         if (!$response) {
//             throw $this->createNotFoundException('No response found for this reclamation.');
//         }
// 
//         // Render the template with the response data
//         return $this->render('response/show.html.twig', [
//             'response' => $response,
//         ]);
//     }
//.....


    //
    
    
    
    

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
