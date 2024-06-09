<?php
namespace App\Controller;

use App\Entity\ShopUser;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Shop user controller.
 */
#[Route('/shop_user')]
class ShopUserController extends MainController
{
    /**
     * Lists all shopUser entities.
     */
    #[Route('/', name: 'shop_user_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $em = $this->em();

        $shopUsers = $em->getRepository(ShopUser::class)->findAll();

        return $this->render('shop_user/index.html.twig', [
            'shopUsers' => $shopUsers,
        ]);
    }

    /**
     * Creates a new shopUser entity.
     */
    #[Route('/new', name: 'shop_user_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $shopUser = new ShopUser();
        $form = $this->createForm('App\Form\ShopUserType', $shopUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->persist($shopUser);
            $em->flush();

            return $this->redirectToRoute('shop_user_show', ['id' => $shopUser->getId()]);
        }

        return $this->render('shop_user/new.html.twig', [
            'shopUser' => $shopUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a shopUser entity.
     */
    #[Route('/{id}', name: 'shop_user_show', methods: ['GET'])]
    public function showAction(ShopUser $shopUser): Response
    {
        $deleteForm = $this->createDeleteForm($shopUser);

        return $this->render('shop_user/show.html.twig', [
            'shopUser' => $shopUser,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing shopUser entity.
     */
    #[Route('/{id}/edit', name: 'shop_user_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, ShopUser $shopUser): Response
    {
        $deleteForm = $this->createDeleteForm($shopUser);
        $editForm = $this->createForm('App\Form\ShopUserType', $shopUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('shop_user_edit', ['id' => $shopUser->getId()]);
        }

        return $this->render('shop_user/edit.html.twig', [
            'shopUser' => $shopUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a shopUser entity.
     */
    #[Route('/{id}', name: 'shop_user_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, ShopUser $shopUser): Response
    {
        $form = $this->createDeleteForm($shopUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($shopUser);
            $em->flush();
        }

        return $this->redirectToRoute('shop_user_index');
    }

    /**
     * Creates a form to delete a shopUser entity.
     *
     * @param ShopUser $shopUser The shopUser entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(ShopUser $shopUser): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shop_user_delete', ['id' => $shopUser->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
