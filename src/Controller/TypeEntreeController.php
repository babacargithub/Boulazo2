<?php
namespace App\Controller;

use App\Entity\TypeEntree;
use App\Form\TypeEntreeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

/**
 * TypeEntree controller.
 */
#[Route('/type_entree')]
class TypeEntreeController extends MainController
{
    /**
     * Lists all TypeEntree entities.
     */
    #[Route('/', name: 'type_entree_index', methods: ['GET'])]
    public function index(): Response
    {
        $em = $this->em();

        $typeEntrees = $em->getRepository(TypeEntree::class)->findAll();

        return $this->render('type_entree/index.html.twig', [
            'typeEntrees' => $typeEntrees,
        ]);
    }

    /**
     * Creates a new TypeEntree entity.
     */
    #[Route('/new', name: 'type_entree_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $typeEntree = new TypeEntree();
        $form = $this->createForm(TypeEntreeType::class, $typeEntree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->persist($typeEntree);
            $em->flush();

            return $this->redirectToRoute('type_entree_show', ['id' => $typeEntree->getId()]);
        }

        return $this->render('type_entree/new.html.twig', [
            'typeEntree' => $typeEntree,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a TypeEntree entity.
     */
    #[Route('/{id}', name: 'type_entree_show', methods: ['GET'])]
    public function show(TypeEntree $typeEntree): Response
    {
        $deleteForm = $this->createDeleteForm($typeEntree);

        return $this->render('type_entree/show.html.twig', [
            'typeEntree' => $typeEntree,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing TypeEntree entity.
     */
    #[Route('/{id}/edit', name: 'type_entree_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeEntree $typeEntree): Response
    {
        $deleteForm = $this->createDeleteForm($typeEntree);
        $editForm = $this->createForm(TypeEntreeType::class, $typeEntree);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('type_entree_edit', ['id' => $typeEntree->getId()]);
        }

        return $this->render('type_entree/edit.html.twig', [
            'typeEntree' => $typeEntree,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a TypeEntree entity.
     */
    #[Route('/{id}/delete', name: 'type_entree_delete', methods: ['DELETE'])]
    public function deleteTypeEntree(Request $request, TypeEntree $typeEntree): Response
    {
        $form = $this->createDeleteForm($typeEntree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($typeEntree);
            $em->flush();
        }

        return $this->redirectToRoute('type_entree_index');
    }

    /**
     * Creates a form to delete a TypeEntree entity.
     */
    private function createDeleteForm(TypeEntree $typeEntree): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type_entree_delete', ['id' => $typeEntree->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
