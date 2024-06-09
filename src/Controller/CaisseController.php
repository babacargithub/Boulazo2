<?php

namespace App\Controller;

use App\Entity\AbstractCaisse;
use App\Entity\Caisse;
use App\Entity\HistoCaisse;
use App\Utils\DateConstants as DateCons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/caisse_pro')]
class CaisseController extends MainController
{



    public function menuVerticalAction(Request $req): Response
    {
        $liste = [
            ["libelle" => "Opérations Du Jour", "id" => "", "href" => $this->generateUrl("histo_today")],
            ["libelle" => "Créer Nouvelle Caisse", "id" => "", "href" => $this->generateUrl("caisse_pro_new")],
            ["libelle" => "Liste Caisses", "id" => "", "href" => $this->generateUrl("caisse_pro_index")],
            ["libelle" => "Recherche", "id" => "", "href" => $this->generateUrl("caisse_pro_new")],
            ["libelle" => "Plus", "id" => "", "href" => "#",
                "dropdown" => [
                    ["href" => $this->generateUrl("caissier_new"), "libelle" => "Créer Nouveau Caissier"],
                    ["href" => $this->generateUrl("caissier_index"), "libelle" => "Liste Des Caissiers"],
                    ["href" => $this->generateUrl("caisse_user_new"), "libelle" => "Affecter Caissier"],
                    ["href" => $this->generateUrl("caissier_index"), "libelle" => "Gestion Affectation"]
                ]
            ]
        ];
        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }

    #[Route('/', name: 'caisse_pro_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $caisses = $this->em()->getRepository(Caisse::class)->findAll();

        return $this->render('caisse/index.html.twig', [
            'caisses' => $caisses,
        ]);
    }

    #[Route('/new', name: 'caisse_pro_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $caisse = new Caisse();
        $form = $this->createForm('App\Form\CaisseType', $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($caisse);
            $this->em()->flush();

            return $this->redirectToRoute('caisse_pro_show', ['id' => $caisse->getId()]);
        }

        return $this->render('caisse/new.html.twig', [
            'caisse' => $caisse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'caisse_pro_show', methods: ['GET'])]
    public function showAction(Caisse $caisse): Response
    {
        $deleteForm = $this->createDeleteForm($caisse);

        return $this->render('caisse/show.html.twig', [
            'caisse' => $caisse,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'caisse_pro_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, Caisse $caisse): Response
    {
        $deleteForm = $this->createDeleteForm($caisse);
        $editForm = $this->createForm('App\Form\CaisseType', $caisse);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('caisse_pro_edit', ['id' => $caisse->getId()]);
        }

        return $this->render('caisse/edit.html.twig', [
            'caisse' => $caisse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'caisse_pro_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, Caisse $caisse): Response
    {
        $form = $this->createDeleteForm($caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->remove($caisse);
            $this->em()->flush();
        }

        return $this->redirectToRoute('caisse_pro_index');
    }

    private function createDeleteForm(Caisse $caisse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caisse_pro_delete', ['id' => $caisse->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    #[Route('/historique_caisse/', name: 'histo_today', methods: ['GET'])]
    public function getHistoOperation(): Response
    {
        $operations =$this->em()->getRepository(HistoCaisse::class)->getListe($this->getActiveCaisse(), DateCons::AUJOURDHUI);
        return $this->render('caisse/histo_op.html.twig', ['operations' => $operations]);
    }

    #[Route('/resume_caisse/{id}/{date_debut}/{date_fin}', name: 'summary_caisse', defaults: ['date_debut' => null, 'date_fin' => null], methods: ['GET'])]
    public function getSummary(Caisse $caisse, Request $req): Response
    {
        $dateDebut = $req->get('date_debut') ?: null;
        $dateFin = $req->get('date_fin') ?: null;

        $em =$this->em();
        $dateExploded = explode('-', $dateDebut);
        $dateCalculated = "$dateExploded[0]-$dateExploded[1]-";
        $data = [];

        for ($i = 1; $i <= intval(date('d')); $i++) {
            $iDate = $i <= 9 ? "0$i" : $i;

            $newDateCalculated = "{$dateCalculated}{$iDate}";
            $new_data = [
                "date" => $newDateCalculated,
                "soldeDebutJournee" => $em->getRepository(Caisse::class)->getSolde($caisse, AbstractCaisse::SODLE_DEBUT_JOUENEE, $newDateCalculated),
                "totalSortie" => $em->getRepository('App\Entity\Sortie')->getTotalSortie($caisse, DateCons::DATE_INTERVALLE, $newDateCalculated, $newDateCalculated),
                "totalEntree" => $em->getRepository('App\Entity\Entree')->getTotalEntree($caisse, DateCons::DATE_INTERVALLE, $newDateCalculated, $newDateCalculated),
                "soldeFinJournee" => $em->getRepository(Caisse::class)->getSolde($caisse, AbstractCaisse::SODLE_FIN_JOUENEE, $newDateCalculated),
            ];
            $data[] = $new_data;
        }

        $totalOperations = $em->getRepository(Caisse::class)->getTotauxJourneeSummary($caisse, DateCons::DATE_INTERVALLE, $dateDebut, $dateFin);

        return $this->render('caisse/resume_caisse.html.twig', [
            'donnees' => $data,
            "caisse" => $caisse
        ]);
    }

    #[Route('/releve_caisse/{id}/{date_debut}/{date_fin}', name: 'releve_caisse', defaults: ['date_debut' => null, 'date_fin' => null], methods: ['GET'])]
    public function getReleve(Caisse $caisse, Request $req): Response
    {
        $dateDebut = $req->get('date_debut') ?: null;
        $dateFin = $req->get('date_fin') ?: null;

        $operations =$this->em()->getRepository(Caisse::class)->getReleve($caisse, DateCons::DATE_INTERVALLE, $dateDebut, $dateFin);

        return $this->render('caisse/releve_caisse.html.twig', ['operations' => $operations, "caisse" => $caisse]);
    }
}
