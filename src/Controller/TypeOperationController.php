<?php
namespace App\Controller;

use App\Entity\TypeOperation;
use App\Form\TypeOperationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Typeoperation controller.
 */
#[Route('/type_operation')]
class TypeOperationController extends MainController
{
    /**
     * Lists all typeOperation entities.
     */
    #[Route('/', name: 'type_operation_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $em = $this->em();

        $typeOperations = $em->getRepository(TypeOperation::class)->findAll();

        return $this->render('typeoperation/index.html.twig', [
            'typeOperations' => $typeOperations,
        ]);
    }

    /**
     * Creates a new typeOperation entity.
     */
    #[Route('/new', name: 'type_operation_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $typeOperation = new TypeOperation();
        $form = $this->createForm(TypeOperationType::class, $typeOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->persist($typeOperation);
            $em->flush();

            return $this->redirectToRoute('type_operation_show', ['id' => $typeOperation->getId()]);
        }

        return $this->render('typeoperation/new.html.twig', [
            'typeOperation' => $typeOperation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a typeOperation entity.
     */
    #[Route('/{id}', name: 'type_operation_show', methods: ['GET'])]
    public function showAction(TypeOperation $typeOperation): Response
    {
        $deleteForm = $this->createDeleteForm($typeOperation);

        return $this->render('typeoperation/show.html.twig', [
            'typeOperation' => $typeOperation,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing typeOperation entity.
     */
    #[Route('/{id}/edit', name: 'type_operation_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, TypeOperation $typeOperation): Response
    {
        $deleteForm = $this->createDeleteForm($typeOperation);
        $editForm = $this->createForm(TypeOperationType::class, $typeOperation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('type_operation_edit', ['id' => $typeOperation->getId()]);
        }

        return $this->render('typeoperation/edit.html.twig', [
            'typeOperation' => $typeOperation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a typeOperation entity.
     */
    #[Route('/{id}', name: 'type_operation_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, TypeOperation $typeOperation): Response
    {
        $form = $this->createDeleteForm($typeOperation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($typeOperation);
            $em->flush();
        }

        return $this->redirectToRoute('type_operation_index');
    }

    /**
     * Creates a form to delete a typeOperation entity.
     *
     * @param TypeOperation $typeOperation The typeOperation entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(TypeOperation $typeOperation): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type_operation_delete', ['id' => $typeOperation->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
