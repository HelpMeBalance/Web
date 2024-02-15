<?php

namespace App\Controller;

use App\Entity\DossierMedicale;
use App\Entity\Question;
use App\Form\DossierMedicaleType;
use App\Repository\DossierMedicaleRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dossier/medicale')]
class DossierMedicaleController extends AbstractController
{
    #[Route('/', name: 'app_dossier_medicale_index', methods: ['GET'])]
    public function index(DossierMedicaleRepository $dossierMedicaleRepository): Response
    {
        return $this->render('dossier_medicale/index.html.twig', [
            'dossier_medicales' => $dossierMedicaleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dossier_medicale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $Qrep, ReponseRepository $Rrep): Response
    {
        $dossierMedicale = new DossierMedicale();
        $form = $this->createForm(DossierMedicaleType::class, $dossierMedicale);
        $questions = $Qrep->findAll();
        $reponses = $Rrep->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossierMedicale);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_dossier_medicale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_medicale/new.html.twig', [
            'dossier_medicale' => $dossierMedicale,
            'form' => $form,
            'ques'=>$questions,
            'reps'=>$reponses,
        ]);
    }

    #[Route('/new2/{idq}/{idr}', name: 'handle_form_submission', methods: ['GET', 'POST'])]
    public function new2($idq, $idr, Request $request, EntityManagerInterface $entityManager, QuestionRepository $Qrep, ReponseRepository $Rrep): Response
    {
        $dossierMedicale = new DossierMedicale();
        $dossierMedicale->setQuestion($Qrep->find($idq));
        $dossierMedicale->setReponse($Rrep->find($idr));
        $entityManager->persist($dossierMedicale);
        $entityManager->flush();
        return $this->redirectToRoute('app_dossier_medicale_new', [], Response::HTTP_SEE_OTHER);

    }
    #[Route('/{id}', name: 'app_dossier_medicale_show', methods: ['GET'])]
    public function show(DossierMedicale $dossierMedicale): Response
    {
        return $this->render('dossier_medicale/show.html.twig', [
            'dossier_medicale' => $dossierMedicale,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_medicale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierMedicale $dossierMedicale, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierMedicaleType::class, $dossierMedicale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medicale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_medicale/edit.html.twig', [
            'dossier_medicale' => $dossierMedicale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_medicale_delete', methods: ['POST'])]
    public function delete(Request $request, DossierMedicale $dossierMedicale, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossierMedicale->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossierMedicale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_medicale_index', [], Response::HTTP_SEE_OTHER);
    }
}
