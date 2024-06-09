<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("admin")]
class AdminController extends MainController
{
    // cette méthode est exécutée quand l'utilisateur choisit une application, elle se chargera de vérifier s'il a accès
    // à l'application choisie ou non
    public function menuVerticalAction(Request $req): Response
    {
        $liste = [
            ["libelle" => "Rapport Journée", "id" => "", "href" => $this->generateUrl("histo_today")],
            ["libelle" => "Rapport Mensuel", "id" => "", "href" => $this->generateUrl("histo_today")],
            ["libelle" => "Créer Caisse", "id" => "", "href" => $this->generateUrl("caisse_pro_new")],
            ["libelle" => "Ajouter Caissier", "id" => "", "href" => $this->generateUrl("caissier_new")],
            ["libelle" => "Créer Boutique", "id" => "", "href" => $this->generateUrl("boutique_new")],
            ["libelle" => "Donner Accès Caisse", "id" => "", "href" => $this->generateUrl("caisse_user_new")],
            ["libelle" => "Donner Accès Boutique", "id" => "", "href" => $this->generateUrl("shop_user_new")],
            ["libelle" => "Gérer Accès Boutique", "id" => "", "href" => $this->generateUrl("shop_user_index")],
            ["libelle" => "Gérer Accès Caisse", "id" => "", "href" => $this->generateUrl("caisse_user_index")],
            ["libelle" => "Plus", "id" => "", "href" => "#",
                "dropdown" => [
                    ["href" => $this->generateUrl("app_register"), "libelle" => "Créer Compte Utilisateur"],
                    ["href" => $this->generateUrl("users_index"), "libelle" => "Liste des Utilisateurs"],
                    ["href" => $this->generateUrl("compte_banque_new"), "libelle" => "Créer Compte Banque"],
                ]
            ],
        ];
        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }

    #[Route("/index", name: "caisse_pro_admin_index")]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
