<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pub = new Publication();
        $form = $this->createForm(PublicationType::class, $pub);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pub);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $pub,
            'form' => $form,
        ]);
    }
   
    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication, EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository): Response
    {
        $publication->setVues($publication->getVues()+1);
        $entityManager->flush();
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,'like'=>0,'nbcom'=>count($commentaireRepository->findAllUnderPublication($publication))
        ]);
    }
    #[Route('/{id}/showcoms', name: 'app_publication_show_comments', methods: ['GET'])]
    public function showComments(Publication $publication,CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/showunderpub.html.twig', [
            'commentaires' => $commentaireRepository->findAllUnderPublication($publication),'publication'=>$publication,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publication->setDateM(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/like', name: 'app_publication_like', methods: ['GET', 'POST'])]
    public function like(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $publication->setLikes($publication->getLikes()+1);
        $entityManager->flush();
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,'like'=>1
        ]);
    }
    #[Route('/{id}/dislike', name: 'app_publication_dislike', methods: ['GET', 'POST'])]
    public function dislike(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $publication->setLikes($publication->getLikes()-1);
        $entityManager->flush();
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,'like'=>0
        ]);
    }
    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blogClient', ['page'=>1], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/{idcat}', name: 'app_publication_deleteCat', methods: ['POST'])]
    public function deleteCat(Request $request, Publication $publication, EntityManagerInterface $entityManager,int $idcat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_blogCatClient', ['cat'=>$idcat,'page'=>1], Response::HTTP_SEE_OTHER);
    }
}
