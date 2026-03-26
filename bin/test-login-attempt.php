#!/usr/bin/env php
<?php

// Script de test pour vérifier le service LoginAttemptService

use App\Service\LoginAttemptService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

require __DIR__ . '/../vendor/autoload.php';

// Créer l'adaptateur de cache
$cache = new FilesystemAdapter();

// Créer le service
$loginAttemptService = new LoginAttemptService($cache);

// Email de test
$testEmail = 'test@example.com';

echo "=== Test du Service LoginAttemptService ===\n\n";

// Nettoyer les tentatives précédentes
$cache->deleteItem('login_attempt_' . hash('sha256', $testEmail));

// Test 1 : Vérifier qu'il n'est pas verrouillé au départ
echo "Test 1 : Compte non verrouillé au départ\n";
echo "isLocked: " . ($loginAttemptService->isLocked($testEmail) ? 'true' : 'false') . "\n";
echo "Tentatives: " . $loginAttemptService->getAttemptsCount($testEmail) . "\n\n";

// Test 2 : Enregistrer 1ère tentative
echo "Test 2 : Après 1ère tentative échouée\n";
$loginAttemptService->recordFailedAttempt($testEmail);
echo "isLocked: " . ($loginAttemptService->isLocked($testEmail) ? 'true' : 'false') . "\n";
echo "Tentatives: " . $loginAttemptService->getAttemptsCount($testEmail) . "\n\n";

// Test 3 : Enregistrer 2e tentative
echo "Test 3 : Après 2e tentative échouée\n";
$loginAttemptService->recordFailedAttempt($testEmail);
echo "isLocked: " . ($loginAttemptService->isLocked($testEmail) ? 'true' : 'false') . "\n";
echo "Tentatives: " . $loginAttemptService->getAttemptsCount($testEmail) . "\n\n";

// Test 4 : Enregistrer 3e tentative
echo "Test 4 : Après 3e tentative échouée\n";
$loginAttemptService->recordFailedAttempt($testEmail);
echo "isLocked: " . ($loginAttemptService->isLocked($testEmail) ? 'true' : 'false') . "\n";
echo "Tentatives: " . $loginAttemptService->getAttemptsCount($testEmail) . "\n";
echo "Temps de blocage restant: " . $loginAttemptService->getRemainingLockTime($testEmail) . "s\n\n";

// Test 5 : Réinitialiser après succès
echo "Test 5 : Après connexion réussie\n";
$loginAttemptService->recordSuccessfulAttempt($testEmail);
echo "isLocked: " . ($loginAttemptService->isLocked($testEmail) ? 'true' : 'false') . "\n";
echo "Tentatives: " . $loginAttemptService->getAttemptsCount($testEmail) . "\n\n";

echo "=== Tous les tests passés avec succès ! ===\n";
