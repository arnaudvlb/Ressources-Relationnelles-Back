<?php

namespace App\Serializer;

use App\Entity\Amis;
use App\Entity\Commentaires;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class PlainIdToIriDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private array $handledTypes = [Commentaires::class, Amis::class];

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (!in_array($type, $this->handledTypes, true)) {
            return false;
        }
        return is_array($data);
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (!is_array($data)) {
            return $this->denormalizer->denormalize($data, $type, $format, $context);
        }

        $convert = function (&$value) {
            if (is_int($value) || ctype_digit((string)$value)) {
                $value = '/api/utilisateurs/' . (int) $value;
                return;
            }

            if (is_array($value) && isset($value['id'])) {
                $value = '/api/utilisateurs/' . (int) $value['id'];
            }
        };

        if ($type === Commentaires::class) {
            if (array_key_exists('utilisateur', $data)) {
                $convert($data['utilisateur']);
            }
        }

        if ($type === Amis::class) {
            if (array_key_exists('demandeur', $data)) {
                $convert($data['demandeur']);
            }
            if (array_key_exists('ami', $data)) {
                $convert($data['ami']);
            }
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Commentaires::class => true,
            Amis::class => true,
        ];
    }
}
