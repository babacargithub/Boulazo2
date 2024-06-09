<?php

namespace App\Controller;

use App\Entity\CaisseUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/caisse_user")]
class CaisseUserController extends MainController
{
    #[Route('', name: 'caisse_user_index', methods: ['GET'])]
    public function indexAction(): Response
    {
        $caisseUsers = $this->em()->getRepository(CaisseUser::class)->findAll();

        return $this->render('caisse_user/index.html.twig', [
            'caisseUsers' => $caisseUsers,
        ]);
    }

    #[Route('/nouvelle_affectation', name: 'caisse_user_new', methods: ['GET', 'POST'])]
    public function newAction(Request $request): Response
    {
        $caisseUser = new CaisseUser();
        $caisseUser->setUser($this->getUser())
            ->setCreatedAt(new \DateTime())
            ->setDeleted(false);
        $form = $this->createForm('App\Form\CaisseUserType', $caisseUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($caisseUser);
            $this->em()->flush();

            return $this->redirectToRoute('caisse_user_show', ['id' => $caisseUser->getId()]);
        }

        return $this->render('caisse_user/new.html.twig', [
            'caisseUser' => $caisseUser,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'caisse_user_show', methods: ['GET'])]
    public function showAction(CaisseUser $caisseUser): Response
    {
        $deleteForm = $this->createDeleteForm($caisseUser);

        return $this->render('caisse_user/show.html.twig', [
            'caisseUser' => $caisseUser,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'caisse_user_edit', methods: ['GET', 'POST'])]
    public function editAction(Request $request, CaisseUser $caisseUser): Response
    {
        $deleteForm = $this->createDeleteForm($caisseUser);
        $editForm = $this->createForm('App\Form\CaisseUserType', $caisseUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em()->flush();

            return $this->redirectToRoute('caisse_user_edit', ['id' => $caisseUser->getId()]);
        }

        return $this->render('caisse_user/edit.html.twig', [
            'caisseUser' => $caisseUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'caisse_user_delete', methods: ['DELETE'])]
    public function deleteAction(Request $request, CaisseUser $caisseUser): Response
    {
        $form = $this->createDeleteForm($caisseUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->remove($caisseUser);
            $this->em()->flush();
        }

        return $this->redirectToRoute('caisse_user_index');
    }

    private function createDeleteForm(CaisseUser $caisseUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('caisse_user_delete', ['id' => $caisseUser->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
