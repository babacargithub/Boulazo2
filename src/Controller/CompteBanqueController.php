<?php
namespace App\Controller;

use App\Entity\CompteBanque;
use App\Form\CompteBanqueType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

/**
* Comptebanque controller.
*/
#[Route('/compte_banque')]
class CompteBanqueController extends MainController
{
// Menu vertical pour les sorties
public function menuVertical(Request $req): Response
{
$liste = [
["libelle" => "Opérations Du Jour", "id" => "", "href" => $this->generateUrl("operation_banque_index")],
["libelle" => "Nouvelle Opération", "id" => "", "href" => $this->generateUrl("operation_banque_new")],
["libelle" => "Recherche Opération", "id" => "", "href" => $this->generateUrl("sortie_search_index")],
["libelle" => "Plus", "id" => "", "href" => "#", "dropdown" => [
["href" => $this->generateUrl("compte_banque_new"), "libelle" => "Créer Nouveau Compte"],
["href" => $this->generateUrl("compte_banque_index"), "libelle" => "Liste Des Comptes"],
]],
];
return $this->render('_sidebar_design.html.twig', ["menu_vertical" => $liste]);
}

/**
* Lists all compteBanque entities.
*/
#[Route('/', name: 'compte_banque_index', methods: ['GET'])]
public function index(Request $req): Response
{
$em = $this->em();

$compteBanques = $em->getRepository(CompteBanque::class)->findAll();

return $this->render('comptebanque/index.html.twig', [
'compteBanques' => $compteBanques,
]);
}

/**
* Creates a new compteBanque entity.
*/
#[Route('/nouveau', name: 'compte_banque_new', methods: ['GET', 'POST'])]
public function new(Request $request): Response
{
$compteBanque = new CompteBanque();
$form = $this->createForm(CompteBanqueType::class, $compteBanque);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$em = $this->em();
$em->persist($compteBanque);
$em->flush();

return $this->redirectToRoute('compte_banque_show', ['id' => $compteBanque->getId()]);
}

return $this->render('comptebanque/new.html.twig', [
'compteBanque' => $compteBanque,
'form' => $form->createView(),
]);
}

/**
* Finds and displays a compteBanque entity.
*/
#[Route('/{id}/afficher', name: 'compte_banque_show', methods: ['GET'])]
public function show(CompteBanque $compteBanque): Response
{
$deleteForm = $this->createDeleteForm($compteBanque);

return $this->render('comptebanque/show.html.twig', [
'compteBanque' => $compteBanque,
'delete_form' => $deleteForm->createView(),
]);
}

/**
* Displays a form to edit an existing compteBanque entity.
*/
#[Route('/{id}/edit', name: 'compte_banque_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, CompteBanque $compteBanque): Response
{
$deleteForm = $this->createDeleteForm($compteBanque);
$editForm = $this->createForm(CompteBanqueType::class, $compteBanque);
$editForm->handleRequest($request);

if ($editForm->isSubmitted() && $editForm->isValid()) {
$this->em()->flush();

return $this->redirectToRoute('compte_banque_edit', ['id' => $compteBanque->getId()]);
}

return $this->render('comptebanque/edit.html.twig', [
'compteBanque' => $compteBanque,
'edit_form' => $editForm->createView(),
'delete_form' => $deleteForm->createView(),
]);
}

/**
* Deletes a compteBanque entity.
*/
#[Route('/{id}/supprimer', name: 'compte_banque_delete', methods: ['DELETE'])]
public function deleteCompte(Request $request, CompteBanque $compteBanque): Response
{
$form = $this->createDeleteForm($compteBanque);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$em = $this->em();
$em->remove($compteBanque);
$em->flush();
$this->addFlash("success", "Compte Supprimé avec Succès!");
}

return $this->redirectToRoute('compte_banque_index');
}

/**
* Creates a form to delete a compteBanque entity.
*/
private function createDeleteForm(CompteBanque $compteBanque): FormInterface
{
return $this->createFormBuilder()
->setAction($this->generateUrl('compte_banque_delete', ['id' => $compteBanque->getId()]))
->setMethod('DELETE')
->getForm();
}
}
