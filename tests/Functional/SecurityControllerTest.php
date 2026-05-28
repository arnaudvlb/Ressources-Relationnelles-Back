<?php

namespace App\Tests\Functional;

use App\Entity\RolesUtilisateurs;
use App\Repository\RolesUtilisateursRepository;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecurityControllerTest extends WebTestCase
{
    public function testLoginCheckRouteIsReachable(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login_check');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            '{"message":"This should not be reached"}',
            (string) $client->getResponse()->getContent()
        );
    }

    public function testRegisterRejectsInvalidJsonPayload(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{invalid-json'
        );

        $this->assertResponseStatusCodeSame(400);
        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Payload JSON invalide', $payload['message']);
    }

    public function testRegisterRejectsAlreadyUsedEmail(): void
    {
        $client = static::createClient();

        $utilisateursRepository = $this->createMock(UtilisateursRepository::class);
        $utilisateursRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'alice@example.com'])
            ->willReturn(new \stdClass());

        self::getContainer()->set(UtilisateursRepository::class, $utilisateursRepository);

        $client->request(
            'POST',
            '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'email' => 'alice@example.com',
                'pseudo' => 'alice',
                'password' => 'secret123',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(409);
        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Cet email est déjà utilisé', $payload['message']);
    }

    public function testRegisterRouteCreatesUserWhenPayloadIsValid(): void
    {
        $client = static::createClient();

        $utilisateursRepository = $this->createMock(UtilisateursRepository::class);
        $utilisateursRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'alice@example.com'])
            ->willReturn(null);

        $role = new RolesUtilisateurs();
        $role->setLibelle('ROLE_USER');

        $rolesRepository = $this->createMock(RolesUtilisateursRepository::class);
        $rolesRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['libelle' => 'ROLE_USER'])
            ->willReturn($role);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed-password');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        self::getContainer()->set(UtilisateursRepository::class, $utilisateursRepository);
        self::getContainer()->set(RolesUtilisateursRepository::class, $rolesRepository);
        self::getContainer()->set(UserPasswordHasherInterface::class, $passwordHasher);
        self::getContainer()->set(EntityManagerInterface::class, $entityManager);

        $client->request(
            'POST',
            '/register',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'email' => 'alice@example.com',
                'pseudo' => 'alice',
                'password' => 'secret123',
                'nom' => 'Alice',
                'prenom' => 'Martin',
                'telephone' => '0600000000',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseStatusCodeSame(201);

        $payload = json_decode((string) $client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Inscription réussie', $payload['message']);
        $this->assertSame('alice@example.com', $payload['user']['email']);
        $this->assertSame('alice', $payload['user']['pseudo']);
        $this->assertContains('ROLE_USER', $payload['user']['roles']);
    }
}
