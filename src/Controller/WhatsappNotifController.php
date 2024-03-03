<?php

namespace App\Controller;

use App\Entity\WhatsappNotif;
use App\Form\WhatsappNotifType;
use App\Repository\WhatsappNotifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/whatsapp/notif')]
class WhatsappNotifController extends AbstractController
{
    #[Route('/', name: 'app_whatsapp_notif_index', methods: ['GET'])]
    public function index(WhatsappNotifRepository $whatsappNotifRepository): Response
    {
        return $this->render('whatsapp_notif/index.html.twig', [
            'whatsapp_notifs' => $whatsappNotifRepository->findAll(),
        ]);
        
    }

    #[Route('/new', name: 'app_whatsapp_notif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $whatsappNotif = new WhatsappNotif();
        $form = $this->createForm(WhatsappNotifType::class, $whatsappNotif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($whatsappNotif);
            $entityManager->flush();

            return $this->redirectToRoute('app_whatsapp_notif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('whatsapp_notif/new.html.twig', [
            'whatsapp_notif' => $whatsappNotif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_whatsapp_notif_show', methods: ['GET'])]
    public function show(WhatsappNotif $whatsappNotif): Response
    {
        return $this->render('whatsapp_notif/show.html.twig', [
            'whatsapp_notif' => $whatsappNotif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_whatsapp_notif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WhatsappNotif $whatsappNotif, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WhatsappNotifType::class, $whatsappNotif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_whatsapp_notif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('whatsapp_notif/edit.html.twig', [
            'whatsapp_notif' => $whatsappNotif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_whatsapp_notif_delete', methods: ['POST'])]
    public function delete(Request $request, WhatsappNotif $whatsappNotif, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$whatsappNotif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($whatsappNotif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_whatsapp_notif_index', [], Response::HTTP_SEE_OTHER);
    }
}
