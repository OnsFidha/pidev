<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Entity\WhatsappNotif;
use App\Form\SearchFormType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twilio\Rest\Client;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    
    // #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    // public function index(ReclamationRepository $reclamationRepository): Response
    // {
    //     return $this->render('reclamation/index.html.twig', [
    //         'reclamations' => $reclamationRepository->findAll(),
    //     ]);
    // }

     #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $reclamationRepository->findAll(), // Query
            $request->query->getInt('page', 1), // Page number
            10 // Limit per page
        );

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $pagination,
        ]);
    }


    /////
    #[Route('/back', name: 'app_reclamation_index2', methods: ['GET'])]
    public function index2(Request $request, ReclamationRepository $reclamationRepository,  PaginatorInterface $paginator): Response
    {
         $pagination = $paginator->paginate(
            $reclamationRepository->findAll(), // Query
            $request->query->getInt('page', 1), // Page number
            10 // Limit per page
        );

        return $this->render('reclamation/indexBack.html.twig', [
            'reclamations' => $pagination,
        ]);

        // return $this->render('reclamation/indexBack.html.twig', [
        //     'reclamations' => $reclamationRepository->findAll(),
        // ]);
    }

    /////

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        //whatsapp
        $sid    = "AC0caafa1f675f3f429a7df499c1e754b7";
        $token  = "**********************************";
        $twilio = new Client($sid, $token);
        //
        $reclamation = new Reclamation();
       
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           try {
                $message = $twilio->messages->create(
                    "whatsapp:+21624171676",
                    [
                        "from" => "whatsapp:+14155238886",
                        // "body" => $user->getNom()." a envoyee une Reclamation , vous pouvez le contacter sur ".$user->getNumTel(),
                        "body" => "Il a envoyé une Reclamation, vous pouvez le contacter sur num :",
                    ]
                );

                //
                $whatsappNotif = new WhatsappNotif();
                $whatsappNotif->setText("user name: a ajouter une reclamation ");
                $whatsappNotif->setIdReclam($reclamation);
                $entityManager->persist($whatsappNotif);
                //
               
                $entityManager->persist($reclamation);
                $entityManager->flush();

                  
                
                
                return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', "Failed to send WhatsApp message: " . $e->getMessage());
            }
                    //mailling
                        
                        $email = (new Email())
                        ->from('sana.khiari@esprit.tn')
                        // ->to('majed.smichi@esprit.tn')
                        ->to('ghofrane.belhadef@esprit.tn')
                        ->subject('Reclamation Artist')
                        ->text('Votre demande sera prise en compte et nous vous répondrons dans les meilleurs délais.
                        Vous serez notifiés via une maill les details de traitement de votre reclamation
                        Merci !!');
                        
                    $mailer->send($email);

                //
        }


        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/back', name: 'app_reclamation_show2', methods: ['GET'])]
    public function show2(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/showForAdmin.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    //
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    //
    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }



    //
    #[Route('/{id}/reponse', name: 'app_reclamation_reponse_show', methods: ['GET'])]
    public function showReclamationReponse(Reclamation $reclamation, ReponseRepository $reponseRepository): Response
    {
        // Retrieve the response associated with the given reclamation
        $reponse = $reponseRepository->findOneBy(['relation' => $reclamation]);

        // Check if a response is found
        if (!$reponse) {
            throw $this->createNotFoundException('No response found for this reclamation.');
        }

        // Render the template with the response data
        return $this->render('reponse/showForUser.html.twig', [
            'reponse' => $reponse,
        ]);
    }
    //
    #[Route('/{id}/rep', name: 'app_reclamation_reponse_show2', methods: ['GET'])]
    public function showReclamationReponse2(Reclamation $reclamation, ReponseRepository $reponseRepository): Response
    {
        // Retrieve the response associated with the given reclamation
        $reponse = $reponseRepository->findOneBy(['relation' => $reclamation]);

        // Check if a response is found
        if (!$reponse) {
            throw $this->createNotFoundException('No response found for this reclamation.');
        }

        // Render the template with the response data
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    //
    
    #[Route('/listePdf/download', name:'reclamation_listePdf')]
     
    public function listePdf(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
        ->getRepository(Reclamation::class)
        ->findAll();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/listePdf.html.twig', [
            'reclamations' => $reclamations,
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


    //

    //

    
     
    #[Route('/reclamations/date', name:'reclamations_date')]
    public function reclamationsByDate(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findByDate();
        
        return $this->render('reclamation/indexBack.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


    #[Route('/reclamations/etat', name:'reclamations_etat')]
     
    public function reclamationsByEtat(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findByEtat();
        
        return $this->render('reclamation/indexBack.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

         
    #[Route('/reclamations/type', name:'reclamations_type')]
    
    public function reclamationsByType(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findByType();
        
        return $this->render('reclamation/indexBack.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    //

   
    //
    //search
    #[Route('/reclamations/search', name:'reclamations_search')]
     public function searchReclam(Request $request , ReclamationRepository $reclamationRepository): Response
    {
        // $searchTerm = $request->query->get('searchTerm');
         $searchTerm = $request->query->get('searchTerm');
        dump($searchTerm);
        $reclamations = [];

        if ($searchTerm) {
            // If a search term is provided, perform the search
            $reclamations = $reclamationRepository->searchByTerm($searchTerm);
        } else {
            // If no search term provided, retrieve all reclamations
            $reclamations = $reclamationRepository->findAll();
        }

        // Create the search form
        $form = $this->createForm(SearchFormType::class);

        return $this->render('reclamation/indexBack.html.twig', [
            'form' => $form->createView(),
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/test/mail', name: 'test_mail')]
    public function test(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('sender@example.com')
            ->to('your@mailtrap.io') // Replace with your Mailtrap inbox email or leave as your default to email
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response('Email sent!');
    }


    //
}
