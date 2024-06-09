<?php

namespace App\Controller;

use App\Entity\AbstractTypeOperation;
use App\Entity\Entree;
use App\Entity\HistoCaisse;
use App\Form\EntreeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\DateConstants as Cons;

#[Route("entree")]
class EntreeController extends MainController
{

    // Menu vertical pour les entree
    public function menuVertical(): Response
    {
        $liste = [
            ["libelle" => "Nouvelle Entrée", "id" => "", "href" => $this->generateUrl("entree_new")],
            ["libelle" => "Entrée du Jour", "id" => "", "href" => $this->generateUrl("entree_index")],
            ["libelle" => "Recherche Entrée", "id" => "", "href" => $this->generateUrl("entree_search_index")],
            ["libelle" => "Plus", "id" => "", "href" => "#", "dropdown" => [
                ["href" => $this->generateUrl("type_entree_new"), "libelle" => "Créer Type Entrée"],
                ["href" => $this->generateUrl("type_entree_index"), "libelle" => "Liste Type Entrée"]
            ]]
        ];

        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }

    #[Route("/index", name: "entree_index")]
    public function index(): Response
    {
        $em = $this->em();
        $entrees = $em->getRepository(Entree::class)->findAll();

        return $this->render('entree/index.html.twig', [
            'entrees' => $entrees,
        ]);
    }

    #[Route("/new", name: "entree_new", methods: ["GET", "POST"])]
    public function newAction(RequestStack $requestStack): Response
    {
        $request = $requestStack->getCurrentRequest();
        $entree = new Entree();
        $entree->setUser($this->getUser());
        $entree->setCaisse($this->getActiveCaisse());
        $form = $this->createForm(EntreeType::class, $entree, ['session' => $this->getRequest()->getSession()]);
        $form->handleRequest($request);
        $entree->setDate(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $caisse = $entree->getCaisse();
            // cette classe permet d'enregistrer l'opération entrée dans l'historique des opérations de caisse
            $histoCaisse = new HistoCaisse();
            // Le solde avant opération et le solde après opération sont archivés ainsi que l'heure de l'opération
            $histoCaisse->setMontant($entree->getMontant())
                ->setAncienSolde($caisse->getSolde())
                ->setNouveauSolde($caisse->getSolde() + $entree->getMontant())
                ->setCaisse($caisse)
                ->setTypeOp(AbstractTypeOperation::ENTREE_LIQUIDE)
                ->setDateOp($entree->getDate())
                ->setEntree($entree)
                ->setUser($this->getUser());

            $caisse->setSolde($caisse->getSolde() + $entree->getMontant());
            // On récupère l'entité boutique qui permet de préciser l'opération
            $em = $this->em();
            $em->persist($entree);
            $em->persist($caisse);
            $em->persist($histoCaisse);
            $em->flush();

            return $this->redirectToRoute('entree_index');
        }

        return $this->render('entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}/afficher", name: "entree_show", methods: ["GET"])]
    public function showAction(Entree $entree): Response
    {
        $deleteForm = $this->createDeleteForm($entree);

        return $this->render('entree/show.html.twig', [
            'entree' => $entree,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route("/{id}/modifier", name: "entree_edit", methods: ["GET", "POST"])]
    public function editAction(Request $request, Entree $entree): Response
    {
        $deleteForm = $this->createDeleteForm($entree);
        $editForm = $this->createForm('App\Form\EntreeType', $entree, ['session' => $this->getRequest()->getSession()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('entree_edit', ['id' => $entree->getId()]);
        }

        return $this->render('entree/edit.html.twig', [
            'entree' => $entree,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: "entree_delete", methods: ["POST"])]
    public function deleteAction(Request $request, Entree $entree): Response
    {
        $form = $this->createDeleteForm($entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $histoEntry = $em->getRepository("App\Entity\HistoCaisse")->findOneBy(['entree' => $entree->getId()]);
            if (!is_null($histoEntry)) {
                $histoEntry->setDeleted(true)->setDeletedAt(new \DateTime())->setEntree(null);
                $em->persist($histoEntry);
            }
            $em->remove($entree);
            $em->flush();
        }

        return $this->redirectToRoute('entree_index');
    }

    private function createDeleteForm(Entree $entree): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('entree_delete', ['id' => $entree->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    #[Route("/recherche/", name: "entree_search_index")]
    public function entreeSearchIndexAction(): Response
    {
        $data = [];
        $form = $this->createForm('App\Form\EntreeSearchType', $data);
        return $this->render('entree/search_index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route("/search/get_result", name: "caisse_pro_entree_search", methods: ["POST"])]
    public function entreeSearchAction(Request $req): Response
    {
        $em = $this->em();
        $entreeRepo = $em->getRepository('App\Entity\Entree');
        $entrees = null;
        $total_entree = null;
        $totaux_poste_dep = [];
        $form = $this->createForm('App\Form\EntreeSearchType', []);
        $form->handleRequest($req);
        $caisse = $this->getActiveCaisse();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            switch ($data['type_search']) {
                case 'total':
                    $total_entree = $entreeRepo->getTotalEntree($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
                case 'liste':
                    $entrees = $entreeRepo->getListeEntree($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    $total_entree = $entreeRepo->getTotalEntree($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
                case 'poste_depense':
                    $type = $this->getRepo('Charge')->find(3);
                    $entrees = $entreeRepo->getListeTypeEntree($caisse, $type, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
            }
            $postDepRepo = $em->getRepository('App\Entity\TypeEntree');
            $posteDepenes = $postDepRepo->findAll();

            foreach ($posteDepenes as $posteDep) {
                $tot = [
                    "montant" => $entreeRepo->getTotalEntreeType($caisse, $posteDep, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']),
                    "libelle" => $posteDep->getLibelle()
                ];
                $totaux_poste_dep[] = $tot;
            }
        }

        return $this->render('entree/liste.html.twig', [
            'entrees' => $entrees,
            'total_entree' => $total_entree,
            "total_postes" => $totaux_poste_dep
        ]);
    }
}
