<?php
namespace App\Controller;

use App\Entity\Siniestro;
use App\Entity\DetalleSiniestro;
use App\Form\SiniestroType;
use App\Form\DetalleSiniestroType;
use App\Form\filtro\SiniestroFiltroType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
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
    $em = $doctrine->getManager();

    $siniestro = new Siniestro();
    $form = $this->createForm(SiniestroType::class, $siniestro);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        foreach ($siniestro->getDetalleSiniestros() as $detalle) {
            $detalle->setIdSiniestro($siniestro);
            $em->persist($detalle);
        }

        $em->persist($siniestro);
        $em->flush();

        return $this->redirectToRoute('siniestro_list');
    }

    return $this->render('siniestro/form.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/show/{id}', name: 'siniestro_show')]
    public function show(int $id,ManagerRegistry $doctrine ): Response
    {
        $siniestro = $doctrine->getRepository(Siniestro::class)->find($id);
        return $this->render('siniestro/show.html.twig', ['siniestro' => $siniestro]);
    }

    #[Route('/filtros', name: 'siniestro_filtros')]
    public function filtros(Request $request, EntityManagerInterface $em): Response
    {
    $form = $this->createForm(SiniestroFiltroType::class);
    $form->handleRequest($request);

    // Creamos el QueryBuilder directamente desde EntityManager
    $qb = $em->createQueryBuilder()
             ->select('s')
             ->from(Siniestro::class, 's');

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        if (!empty($data['fechaDesde'])) {
            $qb->andWhere('s.fecha >= :fechaDesde')
               ->setParameter('fechaDesde', $data['fechaDesde']);
        }

        if (!empty($data['fechaHasta'])) {
            $qb->andWhere('s.fecha <= :fechaHasta')
               ->setParameter('fechaHasta', $data['fechaHasta']);
        }

        if (!empty($data['localidad'])) {
            $qb->andWhere('s.localidad = :localidad')
               ->setParameter('localidad', $data['localidad']);
        }

        if (!empty($data['clima'])) {
            $qb->andWhere('s.clima = :clima')
               ->setParameter('clima', $data['clima']);
        }

        if (!empty($data['calle'])) {
            $qb->andWhere('s.calle LIKE :calle')
               ->setParameter('calle', '%'.$data['calle'].'%');
        }

        if (!empty($data['altura'])) {
            $qb->andWhere('s.altura LIKE :altura')
               ->setParameter('altura', '%'.$data['altura'].'%');
        }
    }

    $resultados = $qb->getQuery()->getResult();

    return $this->render('reportes/SiniestroFiltro.html.twig', [
        'form' => $form->createView(),
        'resultados' => $resultados,
    ]);
    }

    #[Route('/export/pdf', name: 'siniestro_export_pdf')]
    public function exportPdf(EntityManagerInterface $em): Response
    {
    $siniestros = $em->getRepository(Siniestro::class)->findAll();

    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    $html = '<h1>Listado de Siniestros</h1><table border="1" style="border-collapse: collapse; width: 100%;">';
    $html .= '<tr><th>Fecha</th><th>Ubicación</th><th>Descripción</th><th>Localidad</th><th>Clima</th><th>Calle</th><th>Altura</th></tr>';

    foreach ($siniestros as $s) {
        $html .= '<tr>
            <td>'.$s->getFecha()?->format('Y-m-d').'</td>
            <td>'.$s->getUbicacion().'</td>
            <td>'.$s->getDescripcion().'</td>
            <td>'.$s->getLocalidad()?->getNombre().'</td>
            <td>'.$s->getClima()?->getNombre().'</td>
            <td>'.$s->getCalle().'</td>
            <td>'.$s->getAltura().'</td>
        </tr>';
    }

    $html .= '</table>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return new Response($dompdf->stream('siniestros.pdf', ["Attachment" => true]));
    }

    #[Route('/visual', name: 'siniestro_visual')]
    public function visual(EntityManagerInterface $em): Response
    {
    $siniestros = $em->getRepository(Siniestro::class)->findAll();

    $data = [];
    foreach ($siniestros as $s) {
        $localidad = $s->getLocalidad() ? $s->getLocalidad()->getNombre() : 'Sin localidad';
        if (!isset($data[$localidad])) {
            $data[$localidad] = 0;
        }
        $data[$localidad]++;
    }

    return $this->render('siniestro/visual.html.twig', [
        'visualData' => json_encode($data)
    ]);
    }

}
