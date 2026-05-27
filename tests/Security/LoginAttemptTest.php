<?php

namespace App\Tests\Security;

use App\Service\LoginAttemptService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

final class LoginAttemptTest extends TestCase
{
    private LoginAttemptService $loginAttemptService;

    protected function setUp(): void
    {
        $this->loginAttemptService = new LoginAttemptService(new ArrayAdapter());
    }

    public function testResetAttempts(): void
    {
        $email = 'reset@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordSuccessfulAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertSame(0, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testOneFailedAttempt(): void
    {
        $email = 'test@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertSame(1, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testTwoFailedAttempts(): void
    {
        $email = 'test@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertSame(2, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testAccountLockedAfterThreeAttempts(): void
    {
        $email = 'locktest@example.com';

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertTrue($this->loginAttemptService->isLocked($email));
        $this->assertSame(3, $this->loginAttemptService->getAttemptsCount($email));
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

        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);

        $this->assertTrue($this->loginAttemptService->isLocked($email));

        $this->loginAttemptService->recordSuccessfulAttempt($email);

        $this->assertFalse($this->loginAttemptService->isLocked($email));
        $this->assertSame(0, $this->loginAttemptService->getAttemptsCount($email));
    }

    public function testDifferentEmailsAreSeparate(): void
    {
        $email1 = 'user1@example.com';
        $email2 = 'user2@example.com';

        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email2);

        $this->assertSame(2, $this->loginAttemptService->getAttemptsCount($email1));
        $this->assertSame(1, $this->loginAttemptService->getAttemptsCount($email2));

        $this->assertFalse($this->loginAttemptService->isLocked($email1));
        $this->assertFalse($this->loginAttemptService->isLocked($email2));
    }
}
