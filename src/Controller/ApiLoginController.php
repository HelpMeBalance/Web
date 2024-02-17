<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return new JsonResponse([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
