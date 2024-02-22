<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\QuestionType;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response as HttpResponse; // To avoid class name conflict

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index')]
    public function index(QuestionRepository $questionRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
            'controller_name' => 'questionController',
            'service' => 1,
            'part' => 5,
            'title' => "question",
            'titlepage' => 'question - ', 'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_question_show', methods: ['GET', 'POST'])]
    public function show(QuestionRepository $questionRepository,ReponseRepository $rep,Question $question, Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $repos = $rep->findBy(['question' => $question]);

        $question = $questionRepository->find($id); // Fetch the question based on the ID in the route

        if (!$question) {
            throw $this->createNotFoundException('No question found for id '.$id);
        }

        $response = new Reponse();
        $response->setQuestion($question); // Associate the response with the question here

        $form = $this->createForm(ReponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_show', ['id' => $id]);
        }

        // Assuming you have other logic here to prepare for rendering the form

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'Listrepons' => $repos,
            'controller_name' => 'questionController',
            'form' => $form,
            'service' => 1,
            'part' => 5,
            'title' => "question",
            'titlepage' => 'question - ',
        ]);

    }

    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
            'controller_name' => 'questionController',
            'service' => 1,
            'part' => 5,
            'title' => "question",
            'titlepage' => 'question - ',
        ]);
    }

    #[Route('/delete/{id}', name: 'app_question_delete')]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('app_question_index', []);
    }
}
