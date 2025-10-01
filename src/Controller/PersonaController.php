<?php
namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/persona')]
class PersonaController extends AbstractController
{
    #[Route('/', name: 'persona_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $personas = $doctrine->getRepository(Persona::class)->findAll();
        return $this->render('persona/list.html.twig', ['personas' => $personas]);
    }

    #[Route('/new', name: 'persona_new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($persona);
            $em->flush();
            return $this->redirectToRoute('persona_list');
        }

        return $this->render('persona/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/edit/{id}', name: 'persona_edit')]
    public function edit(Request $request, Persona $persona, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('persona_list');
        }

        return $this->render('persona/form.html.twig', ['form' => $form->createView()]);
    }
}