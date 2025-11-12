<?php

namespace App\Controller;

use App\Entity\TipoVehiculo;
use App\Form\TipoVehiculoType;
use App\Repository\TipoVehiculoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tipo/vehiculo')]
final class TipoVehiculoController extends AbstractController
{
    #[Route(name: 'app_tipo_vehiculo_index', methods: ['GET'])]
    public function index(TipoVehiculoRepository $tipoVehiculoRepository): Response
    {
        return $this->render('tipo_vehiculo/index.html.twig', [
            'tipo_vehiculos' => $tipoVehiculoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tipo_vehiculo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipoVehiculo = new TipoVehiculo();
        $form = $this->createForm(TipoVehiculoType::class, $tipoVehiculo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipoVehiculo);
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_vehiculo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tipo_vehiculo/new.html.twig', [
            'tipo_vehiculo' => $tipoVehiculo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tipo_vehiculo_show', methods: ['GET'])]
    public function show(TipoVehiculo $tipoVehiculo): Response
    {
        return $this->render('tipo_vehiculo/show.html.twig', [
            'tipo_vehiculo' => $tipoVehiculo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tipo_vehiculo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TipoVehiculo $tipoVehiculo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipoVehiculoType::class, $tipoVehiculo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tipo_vehiculo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tipo_vehiculo/edit.html.twig', [
            'tipo_vehiculo' => $tipoVehiculo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tipo_vehiculo_delete', methods: ['POST'])]
    public function delete(Request $request, TipoVehiculo $tipoVehiculo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoVehiculo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tipoVehiculo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tipo_vehiculo_index', [], Response::HTTP_SEE_OTHER);
    }
}
