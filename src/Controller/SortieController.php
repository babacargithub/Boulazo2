<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\HistoCaisse;
use App\Entity\AbstractTypeOperation as TypeOp;
use App\Entity\TypeSortie;
use App\Form\SortieSearchType;
use App\Entity\AbstractSortie;
use App\Form\SortieType;
use App\Utils\DateConstants as Cons;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Sortie controller.
 */
#[Route('sortie')]
class SortieController extends MainController
{
    // Menu vertical pour les sorties
    public function menuVertical(): Response
    {
        $liste = [
            ["libelle" => "Nouvelle Sortie", "id" => "", "href" => $this->generateUrl("sortie_new")],
            ["libelle" => "Sortie du Jour", "id" => "", "href" => $this->generateUrl("sortie_index")],
            ["libelle" => "Recherche Sortie", "id" => "", "href" => $this->generateUrl("sortie_search_index")],
            ["libelle" => "Plus", "id" => "", "href" => "#", "dropdown" => [
                ["href" => $this->generateUrl("type_sortie_new"), "libelle" => "Créer Type Sortie"],
                ["href" => $this->generateUrl("type_sortie_index"), "libelle" => "Liste Type Sortie"],
            ]],
        ];
        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }

    #[Route("/index", name: "sortie_index", methods: ["GET"])]
    public function index(Request $req): Response
    {
        $em = $this->em();
        $caisse = $this->getCaisse();
        $sorties = $em->getRepository(Sortie::class)->getListeSortie($caisse, Cons::AUJOURDHUI);
        $total = $em->getRepository(Sortie::class)->getTotalSortie($caisse, Cons::AUJOURDHUI);

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'total' => $total,
        ]);
    }

    #[Route("/new", name: "sortie_new", methods: ["GET", "POST"])]
    public function new(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie, ['session' => $this->getRequest()->getSession()]);
        $sortie->setDate(new \DateTime());
        $sortie->setUser($this->getUser());
        $sortie->setType(AbstractSortie::SORTIE_LIQUIE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caisse = $sortie->getCaisse();
            $ancienSolde = $caisse->getSolde();
            $caisse->setSolde($ancienSolde - $sortie->getMontant())
                ->setLastOp(new \DateTime());

            $histoCaisse = new HistoCaisse();
            $histoCaisse->setAncienSolde($ancienSolde)
                ->setCaisse($caisse)
                ->setDateOp($sortie->getDate())
                ->setMontant($sortie->getMontant())
                ->setNouveauSolde($ancienSolde - $sortie->getMontant())
                ->setTypeOp(TypeOp::SORTIE_LIQUIDE)
                ->setSortie($sortie)
                ->setUser($this->getUser());

            $em = $this->em();
            $em->persist($sortie);
            $em->persist($caisse);
            $em->persist($histoCaisse);
            $em->flush();

            return $this->redirectToRoute('sortie_show', ['id' => $sortie->getId()]);
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}/show", name: "sortie_show", methods: ["GET"])]
    public function show(Sortie $sortie): Response
    {
        $deleteForm = $this->createDeleteForm($sortie);

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route("/{id}/edit", name: "sortie_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, Sortie $sortie): Response
    {
        $deleteForm = $this->createDeleteForm($sortie);
        $editForm = $this->createForm(SortieType::class, $sortie, ['session' => $this->getRequest()->getSession()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('sortie_edit', ['id' => $sortie->getId()]);
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route("/delete/{id}", name: "sortie_delete", methods: ["DELETE"])]
    public function deleteSortie(Request $request, Sortie $sortie): Response
    {
        $form = $this->createDeleteForm($sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->em();
            $histoEntry = $em->getRepository(HistoCaisse::class)->findOneBySortie($sortie->getId());
            $histoEntry->setDeleted(true)->setDeletedAt(new \DateTime())->setSortie(null);
            $em->persist($histoEntry);
            $em->remove($sortie);
            $em->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }

    private function createDeleteForm(Sortie $sortie): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sortie_delete', ['id' => $sortie->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    #[Route("/recherche/", name: "sortie_search_index", methods: ["GET"])]
    public function sortieSearchIndex(): Response
    {
        $data = [];
        $form = $this->createForm(SortieSearchType::class, $data);
        return $this->render('sortie/search_index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("search/get_result", name: "caisse_pro_sortie_search", methods: ["POST"])]
    public function sortieSearch(Request $req): Response
    {
        $em = $this->em();
        $sortieRepo = $em->getRepository(Sortie::class);
        $sorties = null;
        $total_sortie = null;
        $form = $this->createForm(SortieSearchType::class, []);
        $form->handleRequest($req);
        $caisse = $this->getActiveCaisse();
        $totaux_poste_dep = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            switch ($data['type_search']) {
                case 'total':
                    $total_sortie = $sortieRepo->getTotalSortie($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
                case 'liste':
                    $sorties = $sortieRepo->getListeSortie($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    $total_sortie = $sortieRepo->getTotalSortie($caisse, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
                case 'poste_depense':
                    $type = $this->getRepo('Charge')->find(3);
                    $sorties = $sortieRepo->getListeTypeSortie($caisse, $type, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']);
                    break;
            }

            $postDepRepo = $em->getRepository(TypeSortie::class);
            $posteDepenes = $postDepRepo->findAll();

            foreach ($posteDepenes as $posteDep) {
                $tot = [
                    "montant" => $sortieRepo->getTotalSortieType($caisse, $posteDep, Cons::DATE_INTERVALLE, $data['date_debut'], $data['date_fin']),
                    "libelle" => $posteDep->getLibelle()
                ];
                $totaux_poste_dep[] = $tot;
            }
        }

        return $this->render('sortie/liste.html.twig', [
            'sorties' => $sorties,
            'total_sortie' => $total_sortie,
            'total_postes' => $totaux_poste_dep,
        ]);
    }
}
