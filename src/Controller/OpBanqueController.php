<?php
namespace App\Controller;

use App\Entity\OpBanque;
use App\Form\OpBanqueType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Opbanque controller.
 */
#[Route('/operation_banque')]
class OpBanqueController extends MainController
{
    public function menuVertical(): Response
    {
        $liste = [
            ["libelle" => "Opérations du jour ", "id" => "", "href" => $this->generateUrl("operation_banque_index")],
            ["libelle" => "Enregistrer versement", "id" => "", "href" => $this->generateUrl("operation_banque_index")],
            ["libelle" => "Enregistrer retrait", "id" => "", "href" => $this->generateUrl("operation_banque_index")],
            ["libelle" => "Recherche Opérations", "id" => "", "href" => $this->generateUrl("operation_banque_index")],
            ["libelle" => "Plus", "id" => "", "href" => "#", "dropdown" => [
                ["href" => $this->generateUrl("operation_banque_index"), "libelle" => "Créer un compte banque"],
                ["href" => $this->generateUrl("operation_banque_index"), "libelle" => "Liste Comptes Banque"]
            ]]
        ];

        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }
    /**
     * Lists all opBanque entities.
     */
    #[Route('/', name: 'operation_banque_index', methods: ['GET'])]
    public function index(): Response
    {
        $em = $this->em();

        $opBanques = $em->getRepository(OpBanque::class)->findAll();

        return $this->render('opbanque/index.html.twig', [
            'opBanques' => $opBanques,
        ]);
    }

    /**
     * Creates a new opBanque entity.
     */
    #[Route('/new', name: 'operation_banque_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $opBanque = new OpBanque();
        $form = $this->createForm(OpBanqueType::class, $opBanque, ['session' => $this->getRequest()->getSession()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteBanqueOperation = $opBanque->getCompte();
            $caisseOperation = $this->getActiveCaisse();
            $opBanque->setUser($this->getUser());
            $opBanque->setDate(new \DateTime());
            $em = $this->em();
            $em->persist($opBanque);

            // Modify the balances based on the operation type
            switch ($opBanque->getTypeOp()) {
                case OpBanque::VERSEMENT:
                    $compteBanqueOperation->augmenterSolde($opBanque->getMontant());
                    $caisseOperation->diminuerSolde($opBanque->getMontant());
                    break;
                case OpBanque::RETRAIT:
                    $compteBanqueOperation->diminuerSolde($opBanque->getMontant());
                    $caisseOperation->augmenterSolde($opBanque->getMontant());
                    break;
                case OpBanque::VIREMENT_RECU:
                    $compteBanqueOperation->augmenterSolde($opBanque->getMontant());
                    break;
                case OpBanque::VIREMENT_EMIS:
                    $compteBanqueOperation->diminuerSolde($opBanque->getMontant());
                    break;
            }

            $em->persist($compteBanqueOperation);
            $em->persist($caisseOperation);
            $em->flush();

            return $this->redirectToRoute('operation_banque_show', ['id' => $opBanque->getId()]);
        }

        return $this->render('opbanque/new.html.twig', [
            'opBanque' => $opBanque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays an opBanque entity.
     */
    #[Route('/{id}/show', name: 'operation_banque_show', methods: ['GET'])]
    public function show(OpBanque $opBanque): Response
    {
        $deleteForm = $this->createDeleteForm($opBanque);

        return $this->render('opbanque/show.html.twig', [
            'opBanque' => $opBanque,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing opBanque entity.
     */
    #[Route('/{id}/edit', name: 'operation_banque_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OpBanque $opBanque): Response
    {
        $deleteForm = $this->createDeleteForm($opBanque);
        $editForm = $this->createForm(OpBanqueType::class, $opBanque, ['session' => $this->getRequest()->getSession()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('operation_banque_edit', ['id' => $opBanque->getId()]);
        }

        return $this->render('opbanque/edit.html.twig', [
            'opBanque' => $opBanque,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes an opBanque entity.
     */
    #[Route('/{id}/delete', name: 'operation_banque_delete', methods: ['DELETE'])]
    public function deleteOpBanque(Request $request, OpBanque $opBanque): Response
    {
        $form = $this->createDeleteForm($opBanque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $em->remove($opBanque);
            $em->flush();
        }

        return $this->redirectToRoute('operation_banque_index');
    }

    /**
     * Creates a form to delete an opBanque entity.
     */
    private function createDeleteForm(OpBanque $opBanque): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operation_banque_delete', ['id' => $opBanque->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
