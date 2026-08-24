<?php

namespace App\Tests\Functional;

use App\Entity\RolesUtilisateurs;
use App\Entity\Utilisateurs;
use App\Repository\RolesUtilisateursRepository;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecurityControllerTest extends WebTestCase
{
    private function getCsrfToken($client): string
    {
        $client->request('GET', '/api/csrf-token');

        $data = json_decode(
            (string) $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $data['token'];
    }

    public function testLoginCheckRouteIsReachable(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $client->request(
            'POST',
            '/api/login_check',
            server: [
                'HTTP_CSRF_TOKEN' => $csrfToken,
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            '{"message":"This should not be reached"}',
            (string) $client->getResponse()->getContent()
        );
    }

    public function testRegisterRejectsInvalidJsonPayload(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $client->request(
            'POST',
            '/api/register',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_CSRF_TOKEN' => $csrfToken,
            ],
            content: '{invalid-json'
        );

        $this->assertResponseStatusCodeSame(400);

        $payload = json_decode(
            (string) $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame('Payload JSON invalide', $payload['message']);
    }

    public function testRegisterRejectsAlreadyUsedEmail(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $rolesRepository = self::getContainer()->get(RolesUtilisateursRepository::class);

        $role = $rolesRepository->findOneBy(['libelle' => 'ROLE_USER']);

        if (!$role) {
            $role = new RolesUtilisateurs();
            $role->setLibelle('ROLE_USER');

            $entityManager->persist($role);
            $entityManager->flush();
        }

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $rolesRepository = self::getContainer()->get(RolesUtilisateursRepository::class);

        $role = $rolesRepository->findOneBy(['libelle' => 'ROLE_USER']);

        if (!$role) {
            $role = new RolesUtilisateurs();
            $role->setLibelle('ROLE_USER');

            $entityManager->persist($role);
            $entityManager->flush();
        }

        $existingUser = new Utilisateurs();

        $existingUser
            ->setNom('Alice')
            ->setPrenom('Martin')
            ->setTelephone('0600000000')
            ->setEmail('alice@example.com')
            ->setPseudo('alice')
            ->setMotDePasse('hashed-password')
            ->setStatusCompte(true)
            ->setRole($role);

        $entityManager->persist($existingUser);
        $entityManager->flush();

        $client->request(
            'POST',
            '/api/register',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_CSRF_TOKEN' => $csrfToken,
            ],
            content: json_encode([
                'email' => 'alice@example.com',
                'pseudo' => 'alice',
                'password' => 'secret123',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(409);

        $payload = json_decode(
            (string) $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame('Cet email est déjà utilisé', $payload['message']);
    }

    public function testRegisterRouteCreatesUserWhenPayloadIsValid(): void
    {
        $client = static::createClient();

        $csrfToken = $this->getCsrfToken($client);

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $rolesRepository = self::getContainer()->get(RolesUtilisateursRepository::class);

        $role = $rolesRepository->findOneBy(['libelle' => 'ROLE_USER']);

        if (!$role) {
            $role = new RolesUtilisateurs();
            $role->setLibelle('ROLE_USER');

            $entityManager->persist($role);
            $entityManager->flush();
        }

        $email = 'alice_' . uniqid() . '@example.com';

        $client->request(
            'POST',
            '/api/register',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_CSRF_TOKEN' => $csrfToken,
            ],
            content: json_encode([
                'email' => $email,
                'pseudo' => 'alice_' . uniqid(),
                'password' => 'secret123',
                'nom' => 'Alice',
                'prenom' => 'Martin',
                'telephone' => '0600000000',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(201);

        $payload = json_decode(
            (string) $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame('Inscription réussie', $payload['message']);
        $this->assertSame($email, $payload['user']['email']);
        $this->assertContains('ROLE_USER', $payload['user']['roles']);
    }
}
