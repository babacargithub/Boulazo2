<?php

namespace App\Controller;

use App\Entity\TypeSortie;
use App\Form\TypeSortieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

/**
 * Typesortie controller.
 */
#[Route('type_sortie')]
class TypeSortieController extends MainController
{
    #[Route('/', name: 'type_sortie_index', methods: ['GET'])]
    public function index(): Response
    {
        $em = $this->em();
        $typeSorties = $em->getRepository(TypeSortie::class)->findAll();

        return $this->render('typesortie/index.html.twig', [
            'typeSorties' => $typeSorties,
        ]);
    }

    #[Route('/new', name: 'type_sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $typeSortie = new TypeSortie();
        $form = $this->createForm(TypeSortieType::class, $typeSortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->persist($typeSortie);
            $em->flush();

            return $this->redirectToRoute('type_sortie_show', ['id' => $typeSortie->getId()]);
        }

        return $this->render('typesortie/new.html.twig', [
            'typeSortie' => $typeSortie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'type_sortie_show', methods: ['GET'])]
    public function show(TypeSortie $typeSortie): Response
    {
        $deleteForm = $this->createDeleteForm($typeSortie);

        return $this->render('typesortie/show.html.twig', [
            'typeSortie' => $typeSortie,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'type_sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeSortie $typeSortie): Response
    {
        $deleteForm = $this->createDeleteForm($typeSortie);
        $editForm = $this->createForm(TypeSortieType::class, $typeSortie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('type_sortie_edit', ['id' => $typeSortie->getId()]);
        }

        return $this->render('typesortie/edit.html.twig', [
            'typeSortie' => $typeSortie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'type_sortie_delete', methods: ['DELETE'])]
    public function deleteTypeSortie(Request $request, TypeSortie $typeSortie): Response
    {
        $form = $this->createDeleteForm($typeSortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($typeSortie);
            $em->flush();
        }

        return $this->redirectToRoute('type_sortie_index');
    }

    private function createDeleteForm(TypeSortie $typeSortie): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type_sortie_delete', ['id' => $typeSortie->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
