<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;


use App\Entity\Caisse;
use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    //put your code here
    protected EntityManagerInterface $em;
    protected RequestStack $request;
    protected $msg = null;
    protected $errorMsg = null;

    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     */
    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request;
    }

    public function em(): EntityManagerInterface
    {

        return $this->em;
    }

    public function save($object): void
    {
        $em = $this->em();
        $em->persist($object);
        $em->flush();
    }

    public function delete($object): bool|Exception
    {
        $em = $this->em();
        $em->remove($object);
        try {
            $em->flush();
            return true;
        } catch (Exception $ex) {
            return $ex;
        }

    }

    public function getRepo($class): EntityRepository
    {

        return $this->em()->getRepository($class);

    }

    //fonction pour envoyer des messages flash selon que la requete est de type ajax ou pas
    public function sendResponse(array $params = array())
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $response = new Response();
            if (isset($params['errorMsg']) && !is_null($params['errorMsg'])) {
                $response->setContent($this->setErrorMessage($params['errorMsg']));
            } elseif (isset($params['msg']) && !is_null($params['msg'])) {
                $response->setContent($this->setMessage($params['msg']));
            }
            return $response;
        } else {
            if (isset($params['view'])) {
                if (isset($params['responseVars']) && is_array($params['responseVars'])) {
                    return $this->render($params['view'], $params['responseVars']);
                } else {
                    return $this->render($params['view']);
                }
            } else {
                return new Response('Une requêt non AJAX doit inclure une vue. Aucun view renseigné dans le controller. Ce message est renvoyé par moi-meme Fonction SendResponse');
            }

        }
    }

    public function setMessage($msg)
    {
        return json_encode(array("code" => 1, "message" => $msg));
    }

    public function setErrorMessage($error)
    {
        return json_encode(array("code" => 0, "message" => $error));
    }


    // quand l'utilisateur se connecte et après qu'il a identifié, on lui affiche la liste des applications. Il devera
    //alors sélécrionner une applicartion et sse connecter à cette dernière
    /**
     * @Route("select/working_shop/", name="shop_selection")
     * @Route("select/working_shop/", name="go_main_app_selection")
     */
    public function appSelectionAction(Request $req)
    {
        $shopRepo = $this->em()->getRepository("\App\Entity\Shop");
        $shops = $shopRepo->findAll();
        return $this->render('app_selection.html.twig', array("shops" => $shops));

    }
    // cette methode est exécuter quand l'utilisateur choisit une application, elle se chargera de vérifier s'il a accès
    // à l'application choisi ou nom
    /**
     * @Route("app/authenticate/{app}", name="go_main_app_authentication")
     */
    public function appAuthenticateAction(Request $req)
    {
        $Authenticator = $this->get('go.authenticator_listener');
        $app = strtoupper($req->get('app'));

        $session = $this->get('session');
        switch ($app) {
            case "SHOP":
                if ($Authenticator->authenticateApp($this->getUser(), "ROLE_AG_BOUT")) {

                    $session->set('connected_app', "SHOP");
                    $Shop = $this->em()->getRepository('GOShopBundle:UserShop')->findByUser($this->getUser());
                    if (null !== $Shop) {
                        if (count($Shop) > 1) {
                            return $this->redirect($this->generateUrl('go_shop_selection'));
                        }
                        $session->set('connected_shop', $Shop[0]->getShop());
                        $session->set('shop_id', $Shop[0]->getShop()->getId());
                        return $this->redirect($this->generateUrl('go_shop_homepage'));

                    } else {
                        return $this->render('app_selection.html.twig', array('errorMsg' => "Vous n'êtes affecté à aucune boutique!"));
                    }
                }
                break;
            case "CARAVANE":
                if ($Authenticator->authenticateApp($this->getUser(), "ROLE_AG_CARAV")) {
                    $session->set('connected_app', "CARAVANE");
                    return $this->redirect($this->generateUrl("go_caravane_homepage"));
                }
                break;
            case "SMS":
                if ($Authenticator->authenticateApp($this->getUser(), "ROLE_AG_SMS")) {
                    $session->set('connected_app', "SMS");
                    return $this->redirect($this->generateUrl("gosms_homepage"));
                }
                break;
            case "CONS":
                if ($Authenticator->authenticateApp($this->getUser(), "ROLE_GP")) {
                    $session->set('connected_app', "CONS");
                    return $this->redirect($this->generateUrl("cons_homepage"));
                }
                break;
            case "ADMIN":
                if ($Authenticator->authenticateApp($this->getUser(), "ROLE_ADMIN")) {
                    $session->set('connected_app', "ADMIN");
                    return $this->redirect($this->generateUrl("easyadmin"));
                }


        }
        return $this->render('app_selection.html.twig', array('errorMsg' => "Vous n'êtes pas autorisé à accéder à cette application"));

    }


    public function getRequest(): Request
    {
        return $this->request->getCurrentRequest();
    }


    /**
     *
     * @param Request $req
     * @Route("/", name="go_caisse_pro_homepage")
     */

    //fonction raccourci pour récupérer la boutique à laquelle l'utilisateur courant est connecté
    public function getShop()
    {
        //return $this->getUser->getShop();
        $session = $this->get('session');

        if ($session->has('shop_id') && is_numeric($session->get('shop_id'))) {
            $shop = $this->getRepo('Shop')->find($session->get('shop_id'));
            if ($shop !== null && $shop instanceof Shop) {
                return $shop;
            }

        }

        throw   new NotFoundHttpException("Grave Erreur! Aucune connexion sur une boutique! L'application est arrêtée!");

    }

    public function getActiveCaissier()
    {
        $caissier = $this->getRepo('Caissier')->findOneByUserAccount($this->getUser()->getId());
        if ($caissier == null) {
            throw new NotFoundHttpException('Aucun caissier trouvé');
        }
        return $caissier;

    }

    //fonction raccourici pour récupérer la caisse de la boutique à laquelle l'utilisateur courant est connecté

    public function getCaisse()
    {
        $session = $this->request->getSession();

        if ($session->has('caisse_id') && is_numeric($session->get('caisse_id'))) {
            $caisse = $this->em()->getRepository(Caisse::class)->find($session->get('caisse_id'));
            if ($caisse !== null && $caisse instanceof Caisse) {
                return $caisse;
            }

        }

        throw   new NotFoundHttpException("Erreur Fatale! Caisse Non Trouvée! Le script est arrêté!");


    }

    public function getActiveCaisse()
    {
        return $this->getCaisse();
    }

    // quand l'utilisateur se connecte et après qu'il a identifié, on lui affiche la liste des shops s'il est
    // affecté à plusieurs boutiques. Il devera
    //alors sélécrionner une shop et sse connecter à cette dernière
    /**
     *
     * @param Request $req
     * @Route("/working_caisse_selection/", name="working_caisse_selection")
     **/
    public function caisseSelectionAction(Request $req)
    {

        return $this->render('caisse_selection.html.twig', array("caisses" => $this->em()->getRepository(Caisse::class)
            ->findAll()));

    }
}
