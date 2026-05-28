<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Repository\RolesUtilisateursRepository;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(): JsonResponse
    {
        return new JsonResponse(['message' => 'This should not be reached']);
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UtilisateursRepository $utilisateursRepository,
        RolesUtilisateursRepository $rolesRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(['message' => 'Payload JSON invalide'], 400);
        }

        $requiredFields = ['email', 'pseudo', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                return new JsonResponse(['message' => sprintf('Le champ "%s" est obligatoire', $field)], 400);
            }
        }

        if ($utilisateursRepository->findOneBy(['email' => $data['email']])) {
            return new JsonResponse(['message' => 'Cet email est déjà utilisé'], 409);
        }

        $user = new Utilisateurs();
        $user
            ->setNom(trim((string) ($data['nom'] ?? '')))
            ->setPrenom(trim((string) ($data['prenom'] ?? '')))
            ->setTelephone(trim((string) ($data['telephone'] ?? '')))
            ->setEmail(trim((string) $data['email']))
            ->setPseudo(trim((string) $data['pseudo']))
            ->setPhotoProfil(isset($data['photoProfil']) ? trim((string) $data['photoProfil']) : null)
            ->setStatusCompte(true);

        $user->setMotDePasse($passwordHasher->hashPassword($user, (string) $data['password']));

        $roleUser = $rolesRepository->findOneBy(['libelle' => 'ROLE_USER']);
        if ($roleUser !== null) {
            $user->setRole($roleUser);
        } else {
            return new JsonResponse(['message' => 'Rôle ROLE_USER introuvable'], 500);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Inscription réussie',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'pseudo' => $user->getPseudo(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'telephone' => $user->getTelephone(),
                'roles' => $user->getRoles(),
            ],
        ], 201);
    }
}
