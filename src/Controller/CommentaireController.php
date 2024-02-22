<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(Request $request,CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }
    /*#[Route('/', name: 'app_commentaire_indexPub', methods: ['GET'])]
    public function Pub(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }*/
    #[Route('/{idPublication}/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(int $idPublication,Request $request, EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $publication = $entityManager->getRepository(Publication::class)->find($idPublication);
        $commentaire->setPublication($publication);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,'like'=>0
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDateM(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/like', name: 'app_commentaire_like', methods: ['GET', 'POST'])]
    public function like(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $commentaire->setLikes($commentaire->getLikes()+1);
        $entityManager->flush();
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,'like'=>1
        ]);
    }
    #[Route('/{id}/dislike', name: 'app_commentaire_dislike', methods: ['GET', 'POST'])]
    public function dislike(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $commentaire->setLikes($commentaire->getLikes()-1);
        $entityManager->flush();
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,'like'=>0
        ]);
    }
    #[Route('/{id}/{idp}/{showmore}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager,int $showmore,int $idp): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blogDetails', ['id'=>$idp,'showmore'=>$showmore], Response::HTTP_SEE_OTHER);
    }
}
