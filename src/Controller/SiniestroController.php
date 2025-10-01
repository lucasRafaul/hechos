<?php
namespace App\Controller;

use App\Entity\Siniestro;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/siniestro')]
class SiniestroController extends AbstractController
{
    #[Route('/', name: 'siniestro_list')]
    public function list(): Response
    {
        $siniestros = [];
        return $this->render('siniestro/listado.html.twig', ['siniestros' => $siniestros]);
    }

    #[Route('/new', name: 'siniestro_new')]
    public function new(Request $request): Response
    {
        return $this->render('siniestro/form.html.twig', ['siniestro' => null]);
    }

    #[Route('/show/{id}', name: 'siniestro_show')]
    public function show(int $id): Response
    {
        $siniestro = null;
        return $this->render('siniestro/show.html.twig', ['siniestro' => $siniestro]);
    }
}
