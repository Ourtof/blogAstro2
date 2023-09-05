<?php

namespace App\Controller;

use App\Entity\Illustration;
use App\Form\IllustrationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IllustrationRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/illustration')]
class IllustrationController extends AbstractController
{
    #[Route('/', name: 'illustration_index', methods: ['GET'])]
    public function index(IllustrationRepository $illustrationRepository): Response
    {
        return $this->render('illustration/index.html.twig', [
            'illustrations' => $illustrationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'illustration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, #[Autowire('%uploads_dir%')] string $uploadsDir): Response
    {
        $illustration = new Illustration();
        $form = $this->createForm(IllustrationType::class, $illustration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // S'assure qu'on récupère bien les données dans le fichier
            if ($nomFichier = $form['nomFichier']->getData()) {
                dump($uploadsDir);
                $filename = bin2hex(random_bytes(6)) . '.' . $nomFichier->guessExtension();
                $nomFichier->move($uploadsDir, $filename);
                $illustration->setNomFichier($filename);
            }

            
            
            
            // if() {
                // $nomFichier->remove($uploadsDir, $filename);
            // }

            $entityManager->persist($illustration);
            $entityManager->flush();

            return $this->redirectToRoute('illustration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('illustration/new.html.twig', [
            'illustration' => $illustration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'illustration_show', methods: ['GET'])]
    public function show(Illustration $illustration): Response
    {
        return $this->render('illustration/show.html.twig', [
            'illustration' => $illustration,
        ]);
    }

    #[Route('/{id}/edit', name: 'illustration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Illustration $illustration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IllustrationType::class, $illustration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('illustration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('illustration/edit.html.twig', [
            'illustration' => $illustration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'illustration_delete', methods: ['POST'])]
    public function delete(Request $request, Illustration $illustration, EntityManagerInterface $entityManager, #[Autowire('%uploads_dir%')] string $uploadsDir): Response
    {
        if ($this->isCsrfTokenValid('delete'.$illustration->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            $filesystem->remove($uploadsDir . "/" . $illustration->getNomFichier());
            
            $entityManager->remove($illustration);
            $entityManager->flush();
        }
        
        

        return $this->redirectToRoute('illustration_index', [], Response::HTTP_SEE_OTHER);
    }
}
