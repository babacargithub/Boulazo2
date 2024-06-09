<?php
namespace App\Controller;

use App\Entity\Shop;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Shop controller.
 */
#[Route('/boutique')]
class ShopController extends MainController
{
    public function menuVerticalAction(): Response
    {
        $liste = [
            ["libelle" => "Liste des Boutiques", "id" => "", "href" => $this->generateUrl("boutique_index")],
            ["libelle" => "Créer Nouveau", "id" => "", "href" => $this->generateUrl("boutique_new")]
        ];

        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }

    /**
     * Lists all shop entities.
     */
    #[Route('/', name: 'boutique_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $em = $this->em();

        $shops = $em->getRepository(Shop::class)->findAll();

        return $this->render('shop/index.html.twig', [
            'shops' => $shops,
        ]);
    }

    /**
     * Creates a new shop entity.
     */
    #[Route('/nouveau', name: 'boutique_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $shop = new Shop();
        $form = $this->createForm('App\Form\ShopType', $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->persist($shop);
            $em->flush();

            return $this->redirectToRoute('boutique_show', ['id' => $shop->getId()]);
        }

        return $this->render('shop/new.html.twig', [
            'shop' => $shop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a shop entity.
     */
    #[Route('/{id}', name: 'boutique_show', methods: ['GET'])]
    public function showAction(Shop $shop): Response
    {
        $deleteForm = $this->createDeleteForm($shop);

        return $this->render('shop/show.html.twig', [
            'shop' => $shop,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing shop entity.
     */
    #[Route('/{id}/edit', name: 'boutique_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, Shop $shop): Response
    {
        $deleteForm = $this->createDeleteForm($shop);
        $editForm = $this->createForm('App\Form\ShopType', $shop);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('boutique_edit', ['id' => $shop->getId()]);
        }

        return $this->render('shop/edit.html.twig', [
            'shop' => $shop,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a shop entity.
     */
    #[Route('/{id}', name: 'boutique_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, Shop $shop): Response
    {
        $form = $this->createDeleteForm($shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($shop);
            $em->flush();
        }

        return $this->redirectToRoute('boutique_index');
    }

    /**
     * Creates a form to delete a shop entity.
     *
     * @param Shop $shop The shop entity
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(Shop $shop): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('boutique_delete', ['id' => $shop->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
