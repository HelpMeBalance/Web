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
    #[Route('/blogchooseSousCat/{cat}', name: 'app_choosesousCatClient')]
    public function choosesousCat(Request $request, PublicationRepository $publicationRepository,int $cat,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository  ): Response
    {    
        $Cat= $categorieRepository->find($cat);
        return $this->render('frontClient/choosesouscat.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => 'Select Subcategory',
            'titlepage' => 'Blog - ',
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
            'cat'=>$categorieRepository->find($cat),
        ]);
    }
    #[Route('/blogchooseCat', name: 'app_chooseCatClient')]
    public function chooseCat(Request $request, PublicationRepository $publicationRepository,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository  ): Response
    {    
        return $this->render('frontClient/choosecat.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => 'Select Category',
            'titlepage' => 'Blog - ',
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
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
        $formm = $this->createForm(CommentaireType::class, $commentaire);
        $formm->handleRequest($request);
        if ($formm->isSubmitted() && $formm->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_blogDetails', ['id'=>$publication->getId(),'showmore'=>$showmore], Response::HTTP_SEE_OTHER);
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
            'form' => $formm,
        ]);
    }
    #[Route('/blogDetails/{idp}/{showmore}/{idc}', name: 'app_blogDetails_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,int $showmore, int $idc,int $idp, EntityManagerInterface $entityManager,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository,PublicationRepository $publicationRepository): Response
    {
        $publication=$publicationRepository->find($idp);
        $Cat=$publication->getCategorie();
        $newcommentaire = new Commentaire();
        $newcommentaire->setPublication($publication);
        $commentaireupdate=new Commentaire();
        $commentaireupdate= $commentaireRepository->find($idc);
        $formupdate = $this->createForm(CommentaireType::class, $commentaireupdate);
        $formupdate->handleRequest($request);
        if ($formupdate->isSubmitted() && $formupdate->isValid()) {
            $commentaireupdate->setDateM(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_blogDetails', ['id'=>$publication->getId(),'showmore'=>$showmore], Response::HTTP_SEE_OTHER);
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
            'commentaire' => $newcommentaire,
            'commentaireupdate' => $commentaireupdate,
            'formupdate' => $formupdate,
        ]);
    }
    #[Route('/newpub/{cat}/{souscat}', name: 'app_publication_new_blogClient')]
    public function addpub(int $cat,int $souscat,Request $request, EntityManagerInterface $entityManager,PublicationRepository $publicationRepository,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository): Response
    {
        $pub = new Publication();
        $form = $this->createForm(PublicationType::class, $pub);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pub);
            $entityManager->flush();

            return $this->redirectToRoute('app_blogCatClient', ['cat'=>$cat,'page'=>1], Response::HTTP_SEE_OTHER);
        }
        return $this->render('frontClient/addpub.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => 'Share Your Story',
            'titlepage' => 'Blog - ',
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
            'publication' => $pub,
            'form' => $form,
            'cat'=>$categorieRepository->find($cat),
            'souscat'=>$sousCategorieRepository->find($souscat),
        ]);
    }
    #[Route('/updatepub/{idp}', name: 'app_publication_update_blogClient')]
    public function updatepub(int $idp,Request $request, EntityManagerInterface $entityManager,PublicationRepository $publicationRepository,CommentaireRepository $commentaireRepository,SousCategorieRepository $sousCategorieRepository,CategorieRepository $categorieRepository): Response
    {
        $pub = new Publication();
        $pub=$publicationRepository->find($idp);
        $form = $this->createForm(PublicationType::class, $pub);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pub->setDateM(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_blogDetails', ['id'=>$idp,'showmore'=>0], Response::HTTP_SEE_OTHER);
        }
        return $this->render('frontClient/modifpub.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => 'Update Your Post',
            'titlepage' => 'Blog - ',
            'commentaireRepository'=>$commentaireRepository,
            'souscategories'=> $sousCategorieRepository->findAll(),
            'categories'=> $categorieRepository->findAll(),
            'reccpublications' => $publicationRepository->findAllsorted(),
            'publication' => $pub,
            'form' => $form,
            'cat'=>$pub->getCategorie(),
            'souscat'=>$pub->getSousCategorie(),
        ]);
    }
}
