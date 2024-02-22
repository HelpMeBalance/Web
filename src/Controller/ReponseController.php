<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

//    #[Route('/new/{dq}', name: 'app_reponse_new')]
//    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $qrep, ReponseRepository $rep, $dq): Response
//    {
//        $repos = $rep->findBy(['question' => $qrep->find($dq)]);
//        $reponse = new Reponse();
//        $form = $this->createForm(ReponseType::class, $reponse);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $reponse->setQuestion($qrep->find($dq));
//            $entityManager->persist($reponse);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_question_show', ['id' => $dq]);
//        }
//
//        return $this->render('question/show.html.twig', [
//            'question' => $qrep->find($dq),
//            'Listrepons' => $repos,
//            'controller_name' => 'questionController',
//            'form' => $form,
//            'service' => 1,
//            'part' => 5,
//            'title' => "question",
//            'titlepage' => 'question - ',
//        ]);
//
//    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/{idq}/edit', name: 'app_reponse_edit')]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager, $idq): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_show', ['id'=>$idq], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
            'controller_name' => 'questionController',
            'service' => 1,
            'part' => 5,
            'title' => "question" ,
            'titlepage' => 'question - ',
        ]);
    }

    #[Route('/{id}/{idq}', name: 'app_reponse_delete')]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager, $idq): Response
    {
            $entityManager->remove($reponse);
            $entityManager->flush();

        return $this->redirectToRoute('app_question_show', ['id'=>$idq], Response::HTTP_SEE_OTHER);
    }
}
