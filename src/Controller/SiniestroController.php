<?php
namespace App\Controller;

use App\Entity\Siniestro;
use App\Form\SiniestroType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/siniestro')]
class SiniestroController extends AbstractController
{
    #[Route('/', name: 'siniestro_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $siniestros = $doctrine->getRepository(Siniestro::class)->findAll();
        return $this->render('siniestro/listado.html.twig', ['siniestros' => $siniestros]);
    }

    #[Route('/new', name: 'siniestro_new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $siniestro = new Siniestro();
        $form = $this->createForm(SiniestroType::class, $siniestro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($siniestro);
            $em->flush();
            return $this->redirectToRoute('siniestro_list');
        }

        return $this->render('siniestro/form.html.twig', [
            'form' => $form->createView(),
            'siniestro' => $siniestro,
        ]);
    }

    #[Route('/show/{id}', name: 'siniestro_show')]
    public function show(int $id,ManagerRegistry $doctrine ): Response
    {
        $siniestro = $doctrine->getRepository(Siniestro::class)->find($id);
        return $this->render('siniestro/show.html.twig', ['siniestro' => $siniestro]);
    }
}
