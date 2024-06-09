<?php

namespace App\Controller;

use App\Entity\Caissier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/caissier")]
class CaissierController extends MainController
{
    #[Route('/', name: 'caissier_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $caissiers = $this->em()->getRepository(Caissier::class)->findAll();

        return $this->render('caissier/index.html.twig', [
            'caissiers' => $caissiers,
        ]);
    }

    #[Route('/new', name: 'caissier_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $caissier = new Caissier();
        $form = $this->createForm('App\Form\CaissierType', $caissier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($caissier);
            $this->em()->flush();

            return $this->redirectToRoute('caissier_show', ['id' => $caissier->getId()]);
        }

        return $this->render('caissier/new.html.twig', [
            'caissier' => $caissier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'caissier_show', methods: ['GET'])]
    public function showAction(Caissier $caissier): Response
    {
        $deleteForm = $this->createDeleteForm($caissier);

        return $this->render('caissier/show.html.twig', [
            'caissier' => $caissier,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'caissier_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, Caissier $caissier): Response
    {
        $deleteForm = $this->createDeleteForm($caissier);
        $editForm = $this->createForm('App\Form\CaissierType', $caissier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('caissier_edit', ['id' => $caissier->getId()]);
        }

        return $this->render('caissier/edit.html.twig', [
            'caissier' => $caissier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'caissier_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, Caissier $caissier): Response
    {
        $form = $this->createDeleteForm($caissier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->remove($caissier);
            $this->em()->flush();
        }

        return $this->redirectToRoute('caissier_index');
    }

    private function createDeleteForm(Caissier $caissier): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caissier_delete', ['id' => $caissier->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
