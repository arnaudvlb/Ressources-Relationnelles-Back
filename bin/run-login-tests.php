<?php

namespace App\Tests\Security;

use App\Service\LoginAttemptService;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class LoginAttemptTest
{
    private LoginAttemptService $loginAttemptService;
    private ArrayAdapter $cache;

    public function __construct()
    {
        $this->setUp();
    }

    protected function setUp(): void
    {
        $this->cache = new ArrayAdapter();
        $this->loginAttemptService = new LoginAttemptService($this->cache);
    }

    private function getCacheKey(string $email): string
    {
        return 'login_attempts_' . $email;
    }

    public function resetAttempts(string $email): void
    {
        $this->cache->deleteItem($this->getCacheKey($email));
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

    public function testNoLockAtStart(): void
    {
        $this->setUp();
        $email = 'nolock@example.com';
        $this->assertFalse($this->loginAttemptService->isLocked($email), 'Account should not be locked at start');
        $this->assertEquals(0, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be 0 at start');
    }

    public function testOneFailedAttempt(): void
    {
        $this->setUp();
        $email = 'test@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->assertFalse($this->loginAttemptService->isLocked($email), 'Account should not be locked after 1 attempt');
        $this->assertEquals(1, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be 1');
    }

    public function testTwoFailedAttempts(): void
    {
        $this->setUp();
        $email = 'test@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->assertFalse($this->loginAttemptService->isLocked($email), 'Account should not be locked after 2 attempts');
        $this->assertEquals(2, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be 2');
    }

    public function testAccountLockedAfterThreeAttempts(): void
    {
        $this->setUp();
        $email = 'locktest@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->assertTrue($this->loginAttemptService->isLocked($email), 'Account should be locked after 3 attempts');
        $this->assertEquals(3, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be 3');
    }

    public function testRemainingLockTime(): void
    {
        $this->setUp();
        $email = 'locktime@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $remainingTime = $this->loginAttemptService->getRemainingLockTime($email);
        $this->assertGreaterThan(0, $remainingTime, 'Remaining lock time should be > 0');
        $this->assertLessThanOrEqual(300, $remainingTime, 'Remaining lock time should be <= 300 seconds');
    }

    public function testSuccessfulAttemptClearsLock(): void
    {
        $this->setUp();
        $email = 'successtest@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->assertTrue($this->loginAttemptService->isLocked($email), 'Account should be locked after 3 attempts');
        $this->loginAttemptService->recordSuccessfulAttempt($email);
        $this->assertFalse($this->loginAttemptService->isLocked($email), 'Account should be unlocked after successful attempt');
        $this->assertEquals(0, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be reset to 0');
    }

    public function testDifferentEmailsAreSeparate(): void
    {
        $this->setUp();
        $email1 = 'user1@example.com';
        $email2 = 'user2@example.com';
        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email1);
        $this->loginAttemptService->recordFailedAttempt($email2);
        $this->assertEquals(2, $this->loginAttemptService->getAttemptsCount($email1), 'Email1 should have 2 attempts');
        $this->assertEquals(1, $this->loginAttemptService->getAttemptsCount($email2), 'Email2 should have 1 attempt');
        $this->assertFalse($this->loginAttemptService->isLocked($email1), 'Email1 should not be locked');
        $this->assertFalse($this->loginAttemptService->isLocked($email2), 'Email2 should not be locked');
    }

    public function testResetAttempts(): void
    {
        $this->setUp();
        $email = 'reset@example.com';
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->loginAttemptService->recordFailedAttempt($email);
        $this->resetAttempts($email);
        $this->assertFalse($this->loginAttemptService->isLocked($email), 'Account should not be locked after reset');
        $this->assertEquals(0, $this->loginAttemptService->getAttemptsCount($email), 'Attempts count should be 0 after reset');
    }
}
