<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\CaisseUser;
use App\Entity\Caissier;
use App\Entity\Shop;
use App\Entity\ShopUser;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends MainController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login2.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/select/working_shop', name: 'shop_selection')]
    #[Route(path: '/select/working_shop', name: 'choose_working_shop')]
    #[Route(path: '/select/working_shop', name: 'go_main_app_selection')]
    public function chooseWorking(Request $req): Response
    {
        $shopRepo = $this->em()->getRepository(Shop::class);
        $shops = $shopRepo->findAll();
        return $this->render('shop_selection.html.twig', ["shops" => $shops]);

    }


    #[Route("/shop_access/{id}/authenticate", name: "authenticate_shop_access")]
    public function shopAccessAuthenticateAction(Shop $shop, Request $req): Response
    {
        $shopRepo = $this->em()->getRepository(Shop::class);
        $shops = $shopRepo->findAll();

        $session = $req->getSession();

        $ShopAccess = $this->em()->getRepository(ShopUser::class)->findOneBy(['user' => $this->getUser(), "shop" => $shop]);
        if (null !== $ShopAccess) {

            $session->set('connected_shop', $shop);
            $session->set('shop_id', $shop->getId());
//            return $this->redirectToRoute("working_caisse_selection");
            return $this->render('caisse_selection.html.twig', ["caisses" => $this->em()->getRepository(Caisse::class)
                ->findAll()]);

        } else {
            return $this->render('shop_selection.html.twig', ["shops" => $shops, 'errorMsg' => "Vous n'êtes pas autorisé à accéder la boutique sélectionnée!"]);

        }


    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     *
     * @param Caisse $caisse
     * @param Request $req
     * @return Response
     */
    #[Route("/caisse_access/{id}/authenticate", name: "authenticate_caisse_access")]
    public function caisseAccessAuthenticateAction(Caisse $caisse, Request $request)
    {
        $session = $request->getSession();
        $caissier = $this->getRepo(Caissier::class)->findOneByUserAccount($this->getUser()->getId());
        if ($caissier == null) {
            throw new NotFoundHttpException('Aucun caissier trouvé');
        }
        $caisses = $this->getRepo(Caisse::class)->findAll();
        $ShopAccess = $this->em()->getRepository(CaisseUser::class)->findOneBy(['caissier' => $caissier, "caisse" => $caisse]);
        $responseParams = ['errorMsg' => null, "caisses" => $caisses];
        if (null !== $ShopAccess) {

            $session->set('connected_caisse', $caisse);
            $session->set('caisse_id', $caisse->getId());
            $successMsg = "Authentification réussie! Vous êtes autorisés à accéder à la caisse sélectionnée";
            $this->addFlash('success', $successMsg);
            return $this->redirectToRoute("go_caisse_pro_homepage");
        } else {
            $errorMsg = "Vous n'êtes pas autorisé à accéder la caisse sélectionnée!";
            $this->addFlash('error', $errorMsg);
            $responseParams['errorMsg'] = $errorMsg;

        }


        return $this->render('caisse_selection.html.twig', $responseParams);

    }
    #[Route("users", name: "users_index")]
    public function listUsers(): Response
    {
        return $this->render('security/users.html.twig',["users"=>$this->em()->getRepository(User::class)->findAll()]);

    }
}
