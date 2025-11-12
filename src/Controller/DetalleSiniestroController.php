<?php
namespace App\Controller;

use App\Entity\DetalleSiniestro;
use App\Form\DetalleSiniestroType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/detalle-siniestro')]
class DetalleSiniestroController extends AbstractController
{
    #[Route('/', name: 'detalle_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $detalles = $doctrine->getRepository(DetalleSiniestro::class)->findAll();
        return $this->render('detalleSiniestro/listado.html.twig', ['detalles' => $detalles]);
    }

    #[Route('/new', name: 'detalle_new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $detalle = new DetalleSiniestro();
        $form = $this->createForm(DetalleSiniestroType::class, $detalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($detalle);
            $em->flush();
            return $this->redirectToRoute('detalle_list');
        }

        return $this->render('detalleSiniestro/form.html.twig', [
            'form' => $form->createView(),
            'detalle' => $detalle
        ]);
    }

    #[Route('/show/{id}', name: 'detalle_show')]
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $detalle = $doctrine->getRepository(DetalleSiniestro::class)->find($id);

        if (!$detalle) {
            throw $this->createNotFoundException('Detalle no encontrado');
        }

        return $this->render('detalleSiniestro/show.html.twig', [
            'detalle' => $detalle
        ]);
    }


    
}
