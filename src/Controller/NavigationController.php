<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\Trait\GeneralTrait;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavigationController extends AbstractController
{
	use GeneralTrait;

	#[Route('/home', name: 'home')]
	public function home()
	{
		return $this->render('home/index.html.twig');
	}

    #[IsGranted('ROLE_USER')]
	#[Route('/role/membre', name: 'membre')]
	public function membre()
	{
			
		return $this->render('/role/membre.html.twig', [
			'utilisateur' => $this->getUser()
		]);
	}

    #[IsGranted('ROLE_ADMIN')]
	#[Route('/role/admin', name: 'admin')]
	public function admin()
	{
		return $this->render('role/admin.html.twig', [
			'utilisateurs' => $this->utilisateurRepository->findAll(),
			'utilisateur' => $this->getUser()
		]);
	}
}
