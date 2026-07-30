<?php

namespace App\Serializer;

use App\Entity\Ressources;
use App\Entity\Utilisateurs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RessourcesNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'ressources_normalizer_already_called';

    public function __construct(
        private Security $security,
    ) {}

    public function getSupportedTypes(?string $format): array
    {
        return [Ressources::class => false];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Ressources && !isset($context[self::ALREADY_CALLED]);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;
        $normalizedData = $this->normalizer->normalize($object, $format, $context);

        if (!is_array($normalizedData)) {
            return $normalizedData;
        }

        $currentUser = $this->security->getUser();
        $isAdmin = $currentUser instanceof Utilisateurs && in_array('ROLE_ADMIN', $currentUser->getRoles(), true);
        $isOwner = $currentUser instanceof Utilisateurs
            && $object->getUtilisateur() !== null
            && $object->getUtilisateur()->getId() === $currentUser->getId();

        if (!$isAdmin && !$isOwner) {
            unset($normalizedData['estVisible']);
        }

        return $normalizedData;
    }
}
