<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\Trait\GeneralTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavigationController extends AbstractController
{
	use GeneralTrait;

	 #[Route('/home', name: 'home')]
	public function home(Session $session)
	{
		// TODO: afficher message session
		$return = $this->message($session);

		// return $this->render('navigation/home.html.twig', [
		// 	"return" => $return
		// ]);
		return $this->render('home/index.html.twig', [
			"return" => $return,
		]);
	}

	/**
	 * Nécessite juste d'être connecté
	 * @Route("/membre", name="membre")
	 * @IsGranted("IS_AUTHENTICATED_FULLY")
	 * fonctionne aussi avec ROLE_USER
	*/

    #[IsGranted('ROLE_USER')]
	#[Route('/membre', name: 'membre')]
	public function membre(Session $session)
	{
		//test si un utilisateur est connecté
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		// TODO: afficher message session
		$return = $this->message($session);
			
		return $this->render('/membre.html.twig', [
			'return' => $return,
			'utilisateur' => $this->getUser()
		]);
	}

    #[IsGranted('ROLE_ADMIN')]
	#[Route('/admin', name: 'admin')]
	public function admin(Session $session)
	{
		//récupération de l'utilisateur security>Bundle
		$utilisateur = $this->getUser();

		if (is_null($utilisateur)) {
            $session->set("message", "Merci de vous connecter");
            $message = $this->message($session);
            return $this->redirectToRoute('login', $message);
        }

        if (in_array('ROLE_ADMIN', $utilisateur->getRoles())) {
            return $this->render('admin.html.twig', [
                'utilisateurs' => $this->utilisateurRepository->findAll(),
				'utilisateur' => $this->getUser()
            ]);
        }

		$session->set("message", "Vous n'avez pas le droit d'acceder à la page admin vous avez été redirigé sur cette page");
		$message = $this->message($session);
		
		return $this->redirectToRoute('home', $message);
	}

}
