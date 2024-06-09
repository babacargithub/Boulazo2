<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Main controller.
 *
 * @Route("general")
 */
class MainController extends BaseController{
    //put your code here

    #[Route("welcome", name: "go_caisse_pro_homepage")]
    public function indexAction(): Response
    {

        return $this->render('layout.html.twig');

    }

    public function baseVerticalAction(Request $req)
    {

        $liste = [
            ["libelle" => "A propos de version 2.0.0", "id" => "", "href" => "achat.golob"],
            ["libelle" => "Documentation Shop", "id" => "", "href" => "achat_fact_index.golob", "exception" => true],
            ["libelle" => "Aide", "id" => "", "href" => "achat_show_today.golob"]
        ];
        return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
    }
    
}
