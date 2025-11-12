<?php
namespace App\Controller;

use App\Entity\Siniestro;
use App\Entity\DetalleSiniestro;
use App\Form\SiniestroType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repository\SiniestroRepository;
use App\Repository\DetalleSiniestroRepository;
use App\Form\DetalleSiniestroType;
use App\Form\filtro\SiniestroFiltroType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use DateTime; 
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

    #[Route('/delete/{id}', name: 'siniestro_delete', methods: ['POST'])]
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $siniestro = $em->getRepository(Siniestro::class)->find($id);

        if (!$siniestro) {
            throw $this->createNotFoundException('Siniestro no encontrado.');
        }

        // Protección CSRF
        if ($this->isCsrfTokenValid('delete'.$siniestro->getId(), $request->request->get('_token'))) {
            $em->remove($siniestro);
            $em->flush();
        }

        return $this->redirectToRoute('siniestro_list');
    }


    #[Route('/siniestros-mes', name: 'reporte_siniestros_mes')]
    public function siniestrosPorMes(Request $request, SiniestroRepository $repo): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $datos = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $filtros = $form->getData();
            $datos = $repo->reporteSiniestrosPorMes($filtros);
        }

        return $this->render('reportes/siniestros_por_mes.html.twig', [
            'form' => $form->createView(),
            'datos' => $datos,
        ]);
    }

    #[Route('/reporte/siniestros-mes/excel', name: 'reporte_siniestros_mes_excel')]
    public function exportarSiniestrosMesExcel(SiniestroRepository $repo, Request $request): Response
    {
        $filtros = $request->query->all();
        $datos = $repo->reporteSiniestrosPorMes($filtros);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Siniestros por Mes');

        // Encabezados
        $sheet->setCellValue('A1', 'Período (Año-Mes)');
        $sheet->setCellValue('B1', 'Cantidad de Siniestros');

        // Datos
        $fila = 2;
        foreach ($datos as $row) {
            [$anio, $mes] = explode('-', $row['periodo']);
            $nombreMes = DateTime::createFromFormat('!m', $mes)->format('F');
            $sheet->setCellValue("A{$fila}", "{$anio} - {$nombreMes}");
            $sheet->setCellValue("B{$fila}", $row['cantidad']);
            $fila++;
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="siniestros_por_mes.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }


    #[Route('/siniestros-roles', name: 'reporte_roles_siniestros')]
    public function rolesPorSiniestro(Request $request, SiniestroRepository $repoSiniestro, DetalleSiniestroRepository $repoDetalle): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $roles = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $filtros = $form->getData();
            $roles = $repoDetalle->reporteRolesPorSiniestros($filtros);
        }

        return $this->render('reportes/roles_siniestros.html.twig', [
            'form' => $form->createView(),
            'rolesSiniestro' => $roles,
        ]);
    }

    #[Route('/reporte/estado-alcoholico', name: 'reporte_estado_alcoholico')]
    public function reporteEstadoAlcoholico(Request $request, DetalleSiniestroRepository $repo): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $filtros = $form->getData() ?? [];
        $datos = $repo->reporteEstadoAlcoholico($filtros);

        return $this->render('reportes/estado_alcoholico.html.twig', [
            'form' => $form->createView(),
            'datos' => $datos,
        ]);
    }


    #[Route('/reporte/tipo-vehiculo', name: 'reporte_tipo_vehiculo')]
    public function reporteTipoVehiculo(Request $request, DetalleSiniestroRepository $repo): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $filtros = $form->getData() ?? [];
        $datos = $repo->reporteTipoVehiculo($filtros);

        return $this->render('reportes/tipo_vehiculo.html.twig', [
            'form' => $form->createView(),
            'datos' => $datos,
        ]);
    }


    #[Route('/reporte/sexo', name: 'reporte_por_sexo')]
    public function reportePorSexo(Request $request, DetalleSiniestroRepository $repo): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $filtros = $form->getData() ?? [];
        $datos = $repo->reportePorSexo($filtros);

        return $this->render('reportes/por_sexo.html.twig', [
            'form' => $form->createView(),
            'datos' => $datos,
        ]);
    }


    #[Route('/reporte/localidad', name: 'reporte_por_localidad')]
    public function reportePorLocalidad(Request $request, SiniestroRepository $repo): Response
    {
        $form = $this->createForm(SiniestroFiltroType::class);
        $form->handleRequest($request);

        $filtros = $form->getData() ?? [];
        $datos = $repo->reportePorLocalidad($filtros);

        return $this->render('reportes/por_localidad.html.twig', [
            'form' => $form->createView(),
            'datos' => $datos,
        ]);
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
            <td>'.$s->getClima()?->getDescripcion().'</td>
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
