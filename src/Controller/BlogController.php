<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Entity\SousCategorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\PublicationRepository;
use App\Repository\CommentaireRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

class BlogController extends AbstractController
{
    
    #[Route('/blog/{page}', name: 'app_blogClient')]
    public function index(Request $request, PublicationRepository $publicationRepository, int $page,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($publicationRepository->findAll()); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $publications = $publicationRepository->findPaginated($page, $itemsPerPage);
        return $this->render('frontClient/blog.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => 'Brave Chats',
            'titlepage' => 'Blog - ',
            'publications' => $publications,
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
        ]);
    }
    #[Route('/blogSousCat/{souscat}/{page}', name: 'app_blogSousCatClient')]
    public function indexparSousCat(Request $request, PublicationRepository $publicationRepository,int $souscat, int $page,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository ): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($publicationRepository->findbysouscat($souscat)); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $publications = $publicationRepository->findPaginatedbysouscat($page, $itemsPerPage,$souscat);
        $Souscat= $sousCategorieRepository->find($souscat);
        return $this->render('frontClient/blogparCat.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' =>$Souscat->getNom() ,
            'titlepage' => 'Blog - ',
            'publications' => $publications,
            'reccpublications' => $publicationRepository->findAllsorted(),
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'cat'=>$Souscat->getCategorie(),
        ]);
    }
    #[Route('/blogCat/{cat}/{page}', name: 'app_blogCatClient')]
    public function indexparCat(Request $request, PublicationRepository $publicationRepository,int $cat, int $page,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository  ): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($publicationRepository->findbycat($cat)); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $publications = $publicationRepository->findPaginatedbycat($page, $itemsPerPage,$cat);
        $Cat= $categorieRepository->find($cat);
        return $this->render('frontClient/blogparCat.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' =>$Cat->getNom() ,
            'titlepage' => 'Blog - ',
            'publications' => $publications,
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
            'cat'=>$Cat,
        ]);
    }
    #[Route('/blogDetails/{id}/{showmore}', name: 'app_blogDetails', methods: ['GET', 'POST'])]
    public function show(Request $request,int $showmore,Publication $publication, EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository,PublicationRepository $publicationRepository): Response
    {
        $publication->setVues($publication->getVues()+1);
        $entityManager->flush();
        $Cat=$publication->getCategorie();
        $commentaire = new Commentaire();
        $commentaire->setPublication($publication);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
        }
        return $this->render('frontClient/blog_details.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' =>$publication->getSousCategorie()->getNom(),
            'titlepage' => 'Blog - ',
            'publication' => $publication,
            'commentaires' => $publication->getCommentaires(),
            'souscategoriesundercat'=>$publication->getCategorie()->getSousCategories(),
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
            'cat'=>$Cat,
            'commentaireRepository'=>$commentaireRepository,
            'showmore'=>$showmore,
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
}
