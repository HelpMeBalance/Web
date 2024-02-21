<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Entity\SousCategorie;
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
    // #[Route('/blog', name: 'app_blogClient')]
    // public function index(PublicationRepository $publicationRepository): Response
    // {
    //     return $this->render('frontClient/blog.html.twig', [
    //         'controller_name' => 'BlogController',
    //         'service'=>1,
    //         'part'=>5,
    //         'title'=>'Brave Chats',
    //         'titlepage'=>'Blog- ',
    //         'publications' => $publicationRepository->findAll(),
    //     ]);
    // }
    #[Route('/blog/{page}', name: 'app_blogClient')]
    public function index(Request $request, PublicationRepository $publicationRepository, int $page,CommentaireRepository $commentaireRepository): Response
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
        ]);
    }
    #[Route('/blogSousCat/{souscat}/{page}', name: 'app_blogSousCatClient')]
    public function indexparSousCat(Request $request, PublicationRepository $publicationRepository,int $souscat, int $page,CommentaireRepository $commentaireRepository,SousCategorieRepository  $sousCategorieRepository  ): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($publicationRepository->findbysouscat($souscat)); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $publications = $publicationRepository->findPaginatedbysouscat($page, $itemsPerPage,$souscat);
        $nomsouscat= $sousCategorieRepository->find($souscat)->getNom();
        return $this->render('frontClient/blog.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' =>$nomsouscat ,
            'titlepage' => 'Blog - ',
            'publications' => $publications,
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'commentaireRepository'=>$commentaireRepository,
        ]);
    }
    #[Route('/blogCat/{cat}/{page}', name: 'app_blogCatClient')]
    public function indexparCat(Request $request, PublicationRepository $publicationRepository,int $cat, int $page,CommentaireRepository $commentaireRepository,CategorieRepository  $CategorieRepository  ): Response
    {
        $itemsPerPage = 4; // Number of items per page
        $totalItems = count($publicationRepository->findbycat($cat)); // Total number of items
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate the total number of pages
        $publications = $publicationRepository->findPaginatedbycat($page, $itemsPerPage,$cat);
        $nomcat= $CategorieRepository->find($cat)->getNom();
        return $this->render('frontClient/blog.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' =>$nomcat ,
            'titlepage' => 'Blog - ',
            'publications' => $publications,
            'totalPages' => $totalPages,
            'curentPage'=>$page,
            'commentaireRepository'=>$commentaireRepository,
        ]);
    }
    #[Route('/blogDetails/{id}', name: 'app_blogDetails', methods: ['GET'])]
    public function show(Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $publication->setVues($publication->getVues()+1);
        $entityManager->flush();
        return $this->render('frontClient/blog_details.html.twig', [
            'controller_name' => 'BlogController',
            'service' => 1,
            'part' => 5,
            'title' => $publication->getCategorie()->getNom(),
            'titlepage' => 'Blog - ',
            'publication' => $publication,
            'commentaires' => $publication->getCommentaires(),
            'souscategories'=>$publication->getCategorie()->getSousCategories(),
        ]);
    }

}
