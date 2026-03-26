<?php

namespace App\Tests\Security;

use App\Service\LoginAttemptService;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class LoginAttemptTest
{
    private LoginAttemptService $loginAttemptService;
    private ArrayAdapter $cache;

    protected function setUp(): void
    {
        // Utiliser un cache en mémoire pour les tests
        $this->cache = new ArrayAdapter();
        $this->loginAttemptService = new LoginAttemptService($this->cache);
    }

    public function assertTrue($condition, $message = ''): void
    {
        if (!$condition) {
            throw new \Exception("Assertion failed: " . $message);
        }
    }

    public function assertFalse($condition, $message = ''): void
    {
        if ($condition) {
            throw new \Exception("Assertion failed: " . $message);
        }
    }

    public function assertEquals($expected, $actual, $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \Exception("Assertion failed: Expected $expected, got $actual. " . $message);
        }
    }

    public function assertGreaterThan($expected, $actual, $message = ''): void
    {
        if ($actual <= $expected) {
            throw new \Exception("Assertion failed: $actual should be > $expected. " . $message);
        }
    }

    public function assertLessThanOrEqual($expected, $actual, $message = ''): void
    {
        if ($actual > $expected) {
            throw new \Exception("Assertion failed: $actual should be <= $expected. " . $message);
        }
    }

    public function testResetAttempts(): void
    {
        $email = 'reset@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordSuccessfulAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertEquals(0, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testOneFailedAttempt(): void
    {
        $email = 'test@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertEquals(1, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testTwoFailedAttempts(): void
    {
        $email = 'test@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertEquals(2, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testAccountLockedAfterThreeAttempts(): void
    {
        $email = 'locktest@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertTrue($this->loginAttemptService->isLocked($email));
        $this->assertEquals(3, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testRemainingLockTime(): void
    {
        $email = 'locktest@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $remainingTime = $this->loginAttemptService->getRemainingLockTime($email);

        $this->assertGreaterThan(0, $remainingTime);
        $this->assertLessThanOrEqual(300, $remainingTime);
    }

    public function testSuccessfulAttemptClearsLock(): void
    {
        $email = 'successtest@example.com';

        // Enregistrer 3 tentatives échouées
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertTrue($this->loginAttemptService->isLocked($email));

        // Enregistrer une connexion réussie
        $this->loginAttemptService->recordSuccessfulAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertEquals(0, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testDifferentEmailsAreSeparate(): void
    {
        $email1 = 'user1@example.com';
        $email2 = 'user2@example.com';

        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email2);

        $this->assertEquals(2, $this->loginAttemptService->getAttemptsCount($email1));
        $this->assertEquals(1, $this->loginAttemptService->getAttemptsCount($email2));

        $this->assertFalse($this->loginAttemptService->isLocked($email1));
        $this->assertFalse($this->loginAttemptService->isLocked($email2));
    }
}
