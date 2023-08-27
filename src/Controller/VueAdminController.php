<?php

namespace App\Controller;

use App\Controller\Trait\GeneralTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VueAdminController extends AbstractController
{
    use GeneralTrait;
    
    #[Route('/admin/vue/admin', name: 'vue_admin')]
    public function index(): Response
    {
        return $this->render('admin/vue_admin/index.html.twig', [
            'controller_name' => 'VueAdminController',
        ]);
    }

    /**
	 * Besoin des droits admin
	 * @Route("/admin", name="admin")
	 * @IsGranted("ROLE_ADMIN")
	*/

	#[Route('/admin', name: 'admin')]
    
	public function admin(Session $session)
	{
		//récupération de l'utilisateur security>Bundle
		$utilisateur = $this->getUser();

        $this->adminConnexion($utilisateur, $session);

		$session->set("message", "Vous n'avez pas le droit d'acceder à la page admin vous avez été redirigé sur cette page");

		$return = $this->message($session);
		
		return $this->redirectToRoute('home', $return);
	}
}
