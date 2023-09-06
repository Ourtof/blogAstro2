<?php

namespace App\Controller\Trait;

use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\Session;

trait GeneralTrait
{
    public function __construct(
       private UtilisateurRepository $utilisateurRepository 
    ) {
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