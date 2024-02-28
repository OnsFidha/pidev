<?php

namespace App\Controller;
use App\Form\SearchType;
use App\Entity\User;
use App\Form\UserType;


use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;




#[Route('/user2')]
class User2Controller extends AbstractController
{
    /**
     * @Route("/search", name="search_route", methods={"POST"})
     */
    #[Route('/search', name: 'search_route', methods: ['POST'])]
    public function search(Request $request)
    {
        // Récupérer le terme de recherche depuis la requête
        $query = $request->request->get('query');
    
        // Si le terme de recherche est vide, retourner un tableau vide
        if (empty($query)) {
            return new JsonResponse([]);
        }
    
        // Rechercher les utilisateurs dont le nom contient le terme de recherche
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        $results = $userRepository->findByPartialName($query);
    
        // Retourner les résultats au format JSON
        return $this->json($results);
    }

    #[Route('/', name: 'app_user2_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user2/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user2/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user2_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user2/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user2/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user2_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user2_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_user2_sort', methods: ['POST'])]
    private function sortUsers(string $sort, EntityManagerInterface $entityManager): array
    {
        // Vérifier si le paramètre de tri est valide
        $validSortFields = ['id', 'email', 'name', 'prename', 'phone', 'isVerified', 'birthday', 'image'];
        if (!in_array($sort, $validSortFields)) {
            throw new \InvalidArgumentException('Invalid sort field');
        }

        // Récupérer les utilisateurs triés selon le paramètre de tri
        return $entityManager->getRepository(User::class)->findBy([], [$sort => 'ASC']);
    }
    #[Route('/listePdf/download', name:'listePdf')]
     
    public function listePdf(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager
        ->getRepository(User::class)
        ->findAll();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user2/listePdf.html.twig', [
            'users' => $user,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="mypdf.pdf"',
        ]);



    }
}
