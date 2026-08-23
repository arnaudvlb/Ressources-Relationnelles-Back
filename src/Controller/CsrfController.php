<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfController extends AbstractController
{
    #[Route('/api/csrf-token', name: 'api_csrf_token', methods: ['GET'])]
    public function token(CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $token = $csrfTokenManager->getToken('submit');

        $response = new JsonResponse([
            'token' => $token->getValue(),
        ]);

        $response->headers->setCookie(
            Cookie::create(
                'csrf-token_' . $token->getValue(),
                'csrf-token',
                0,
                '/',
                null,
                false,
                false,
                false,
                'strict'
            )
        );

        return $response;
    }
}