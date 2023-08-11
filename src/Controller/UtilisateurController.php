<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Controller\Trait\GeneralTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
        use GeneralTrait;

        public function __construct(
                private UtilisateurRepository $utilisateurRepository,
                private EntityManagerInterface $em
        ) {
        }

    #[Route('/', name: 'utilisateur_index', methods: ['GET'])]
    public function index(Session $session): Response
    {
        //besoin de droits admin
        $utilisateur = $this->getUtilisateur();

        $this->adminConnexion($utilisateur, $session);

        return $this->redirectToRoute('home');
    }

    #[Route('/new', name: 'utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurPasswordHasherInterface $passwordHasher, Session $session, UtilisateurRepository $utilisateur): Response|RedirectResponse
    {

        //test de sécurité, un utilisateur connecté ne peut pas s'inscrire
        $utilisateurInterface = $this->getUtilisateur();
        if($utilisateurInterface)
        {
                $session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
                return $this->redirectToRoute('membre');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                // TODO: Doc Symfo encode password, bug sur $utilisateur
                // TODO: à faire vérifier
                $hashedPassword = $passwordHasher->hashPassword(
                    $utilisateur, 
                    $utilisateur->getPassword());
                // $utilisateur->setMotDePasse($passwordHasher->hashPassword($utilisateur, $utilisateur->getMotDePasse()));
                $this->em->persist($utilisateur);
                $this->em->flush();

                return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurPasswordHasherInterface $passwordHasher, Session $session, $id): Response|RedirectResponse
    {
        $utilisateur = $this->utilisateurRepository->find($this->getUtilisateur());

            if($utilisateur->getId() != $id )
            {
                    // un utilisateur ne peut pas en modifier un autre
                    $session->set("message", "Vous ne pouvez pas modifier cet utilisateur");
                    return $this->redirectToRoute('membre');
            }
            $form = $this->createForm(UtilisateurType::class, $utilisateur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $utilisateur, 
                    $utilisateur->getPassword());
                // $utilisateur->setMotDePasse($passwordHasher->hashPassword($utilisateur, $utilisateur->getMotDePasse()));
                $this->em->persist($utilisateur);
                $this->em->flush();

                    return $this->redirectToRoute('utilisateur_index');
            }

            return $this->render('utilisateur/edit.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
            ]);
    }

    #[Route('/{id}', name: 'utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, Session $session, $id): Response|RedirectResponse
    {
        $utilisateur = $this->getUtilisateur();
        if($utilisateur->getId() != $id )
        {
            // un utilisateur ne peut pas en supprimer un autre
            $session->set("message", "Vous ne pouvez pas supprimer cet utilisateur");
            return $this->redirectToRoute('membre');
        }

        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token')))
        {
            $this->em->remove($utilisateur);
            $this->em->flush();
            // permet de fermer la session utilisateur et d'éviter que l'EntityProvider ne trouve pas la session
            $session = new Session();
            $session->invalidate();
        }

        return $this->redirectToRoute('home');
}
}
