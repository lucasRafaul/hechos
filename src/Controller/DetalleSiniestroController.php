<?php
namespace App\Controller;

use App\Entity\DetalleSiniestro;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/detalle-siniestro')]
class DetalleSiniestroController extends AbstractController
{
    #[Route('/assign', name: 'detalle_assign')]
    public function assign(Request $request): Response
    {
        return $this->render('detalleSiniestro/form.html.twig', [
            'detalle' => null
        ]);
    }

    #[Route('/list', name: 'detalle_list')]
    public function list(): Response
    {
        $detalles = [];
        return $this->render('detalleSiniestro/listado.html.twig', [
            'detalles' => $detalles
        ]);
    }
}
