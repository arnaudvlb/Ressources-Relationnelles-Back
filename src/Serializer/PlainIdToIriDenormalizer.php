<?php

namespace App\Serializer;

use App\Entity\Adorer;
use App\Entity\Amis;
use App\Entity\Commentaires;
use App\Entity\Consultations;
use App\Entity\Favoris;
use App\Entity\Message;
use App\Entity\Ressources;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class PlainIdToIriDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const CONTEXT_ALREADY_PROCESSED = 'plain_id_to_iri_denormalizer_already_processed';

    private array $handledTypes = [Commentaires::class, Amis::class, Message::class, Adorer::class, Favoris::class, Consultations::class, Ressources::class];

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        if (!empty($context[self::CONTEXT_ALREADY_PROCESSED])) {
            return false;
        }

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

        $context[self::CONTEXT_ALREADY_PROCESSED] = true;

        $convert = function (&$value, string $prefix) {
            if (is_int($value) || ctype_digit((string)$value)) {
                $value = sprintf('/api/%s/%d', $prefix, (int) $value);
                return;
            }

            if (is_array($value) && isset($value['id'])) {
                $value = sprintf('/api/%s/%d', $prefix, (int) $value['id']);
            }
        };

        foreach (['utilisateur' => 'utilisateurs', 'expediteur' => 'utilisateurs', 'destinataire' => 'utilisateurs', 'demandeur' => 'utilisateurs', 'ami' => 'utilisateurs', 'resource' => 'ressources', 'categorie' => 'categories'] as $field => $prefix) {
            if (array_key_exists($field, $data)) {
                $convert($data[$field], $prefix);
            }
        }

        if ($type === Ressources::class && array_key_exists('categorie_id', $data)) {
            $convert($data['categorie_id'], 'categories');
            $data['categorie'] = $data['categorie_id'];
            unset($data['categorie_id']);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Commentaires::class => true,
            Amis::class => true,
            Message::class => true,
            Adorer::class => true,
            Favoris::class => true,
            Consultations::class => true,
            Ressources::class => true,
        ];
    }
}
