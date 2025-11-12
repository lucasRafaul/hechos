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
            
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $personas = $doctrine->getRepository(Persona::class)->findAll();
        return $this->render('persona/listado.html.twig', ['personas' => $personas]);
    }

    #[Route('/new', name: 'persona_new')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        // Bloquea el acceso si el usuario no tiene el rol ROLE_OPERATOR
        //$this->denyAccessUnlessGranted('ROLE_OPERATOR');
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($persona);
            $em->flush();
            return $this->redirectToRoute('persona_list');
        }

        return $this->render('persona/form.html.twig', ['form' => $form->createView(),'persona' => $persona,]);
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

    #[Route('/delete/{id}', name: 'persona_delete', methods: ['POST'])]
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); 

        $em = $doctrine->getManager();
        $persona = $em->getRepository(Persona::class)->find($id);
        
        if (!$persona) {
            throw $this->createNotFoundException('Persona no encontrada.');
        }

        if ($this->isCsrfTokenValid('delete'.$persona->getId(), $request->request->get('_token'))) {
            $em->remove($persona);
            $em->flush();
        }

        return $this->redirectToRoute('persona_list');
    }

}