<?php

namespace App\Controller;

use App\Entity\Clima;
use App\Form\ClimaType;
use App\Repository\ClimaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clima')]
final class ClimaController extends AbstractController
{
    #[Route(name: 'app_clima_index', methods: ['GET'])]
    public function index(ClimaRepository $climaRepository): Response
    {
        return $this->render('clima/index.html.twig', [
            'climas' => $climaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clima_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $clima = new Clima();
        $form = $this->createForm(ClimaType::class, $clima);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($clima);
            $entityManager->flush();

            return $this->redirectToRoute('app_clima_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clima/new.html.twig', [
            'clima' => $clima,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clima_show', methods: ['GET'])]
    public function show(Clima $clima): Response
    {
        return $this->render('clima/show.html.twig', [
            'clima' => $clima,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clima_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clima $clima, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClimaType::class, $clima);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_clima_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clima/edit.html.twig', [
            'clima' => $clima,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clima_delete', methods: ['POST'])]
    public function delete(Request $request, Clima $clima, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clima->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($clima);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clima_index', [], Response::HTTP_SEE_OTHER);
    }
}
