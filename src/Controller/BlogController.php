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
        $itemsPerPage = 6; // Number of items per page
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
}
