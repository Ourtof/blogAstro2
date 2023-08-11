<?php

namespace App\Controller\Trait;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

trait GeneralTrait
{
    public function __construct(
       private UtilisateurRepository $utilisateurRepository 
    ) {
    }
 
    public function adminConnexion(UserInterface $utilisateur, Session $session) {
        if(is_null($utilisateur)) {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('login');
        } else if(in_array('ROLE_ADMIN', $utilisateur->getRoles())){
            return $this->render('utilisateur/index.html.twig', [
                'utilisateurs' => $this->utilisateurRepository->findAll(),
            ]);
        }
    }

    public function message(Session $session) {
        $return = [];
    
        if($session->has('message')) {
            $message = $session->get('message');
            $session->remove('message'); //on vide la variable message dans la session
            $return['message'] = $message; //on ajoute à l'array de paramètres notre message
        }

        return $return;
    }
}