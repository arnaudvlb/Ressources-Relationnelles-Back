<?php

namespace App\Tests\Unit\Serializer;

use App\Entity\Categories;
use App\Entity\Ressources;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class RessourcesCategorySerializationTest extends TestCase
{
    public function testCategoryIsIncludedInResourceReadNormalization(): void
    {
        $serializer = new Serializer([
            new ObjectNormalizer(new ClassMetadataFactory(new AttributeLoader())),
        ], [
            new JsonEncoder(),
        ]);

        $categorie = (new Categories())
            ->setLibelle('Développement')
            ->setCouleur('#123456');

        $ressource = (new Ressources())
            ->setTitre('Ressource test')
            ->setContenu('Contenu test')
            ->setCategorie($categorie);

        $normalized = $serializer->normalize($ressource, null, [
            'groups' => ['resource:read'],
        ]);

        $this->assertArrayHasKey('categorie', $normalized);
        $this->assertSame('Développement', $normalized['categorie']['libelle']);
        $this->assertSame('#123456', $normalized['categorie']['couleur']);
    }
}
