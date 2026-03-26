<?php

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

class LoginAttemptService
{
    private const MAX_ATTEMPTS = 3;
    private const LOCK_TIME = 300; // 5 minutes en secondes

    public function __construct(
        private CacheItemPoolInterface $cache
    ) {}

    public function recordFailedAttempt(string $email): void
    {
        $key = $this->getAttemptsKey($email);
        $item = $this->cache->getItem($key);

        $attempts = $item->get() ?? 0;
        $attempts++;

        $item->set($attempts);
        $item->expiresAfter(self::LOCK_TIME);
        $this->cache->save($item);

        // Enregistrer aussi le timestamp d'expiration
        $expiresKey = $this->getExpiresAtKey($email);
        $expiresItem = $this->cache->getItem($expiresKey);
        $expiresItem->set(time() + self::LOCK_TIME);
        $expiresItem->expiresAfter(self::LOCK_TIME);
        $this->cache->save($expiresItem);
    }

    public function recordSuccessfulAttempt(string $email): void
    {
        $key = $this->getAttemptsKey($email);
        $expiresKey = $this->getExpiresAtKey($email);
        $this->cache->deleteItem($key);
        $this->cache->deleteItem($expiresKey);
    }

    public function isLocked(string $email): bool
    {
        $key = $this->getAttemptsKey($email);
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            return false;
        }

        $attempts = $item->get() ?? 0;
        return $attempts >= self::MAX_ATTEMPTS;
    }

    public function getAttemptsCount(string $email): int
    {
        $key = $this->getAttemptsKey($email);
        $item = $this->cache->getItem($key);

        return $item->isHit() ? ($item->get() ?? 0) : 0;
    }

    public function getRemainingLockTime(string $email): int
    {
        $expiresKey = $this->getExpiresAtKey($email);
        $expiresItem = $this->cache->getItem($expiresKey);

        if (!$expiresItem->isHit()) {
            return 0;
        }

        $expiresAt = $expiresItem->get();
        if ($expiresAt === null) {
            return 0;
        }

        $remainingTime = $expiresAt - time();
        return max(0, (int)$remainingTime);
    }

    private function getAttemptsKey(string $email): string
    {
        return 'login_attempt_' . hash('sha256', $email);
    }

    private function getExpiresAtKey(string $email): string
    {
        return 'login_attempt_expires_' . hash('sha256', $email);
    }
}
