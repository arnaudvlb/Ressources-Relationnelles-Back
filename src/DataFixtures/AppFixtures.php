<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Adorer;
use App\Entity\Amis;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\Consultations;
use App\Entity\Favoris;
use App\Entity\Medias;
use App\Entity\Message;
use App\Entity\Partages;
use App\Entity\RefreshToken;
use App\Entity\RenitialisationMdp;
use App\Entity\Ressources;
use App\Entity\RolesUtilisateurs;
use App\Entity\Tags;
use App\Entity\TagsRessources;
use App\Entity\Types;
use App\Entity\Utilisateurs;
use App\Entity\VisibiliteStatut;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $roleUser = (new RolesUtilisateurs())
            ->setLibelle('ROLE_USER');

        $roleAdmin = (new RolesUtilisateurs())
            ->setLibelle('ROLE_ADMIN');

        $manager->persist($roleUser);
        $manager->persist($roleAdmin);

        $userAlice = $this->createUser(
            nom: 'Dupont',
            prenom: 'Alice',
            telephone: '0600000001',
            email: 'alice@example.com',
            pseudo: 'alice',
            role: $roleUser,
            password: 'alice123'
        );

        $userBob = $this->createUser(
            nom: 'Martin',
            prenom: 'Bob',
            telephone: '0600000002',
            email: 'bob@example.com',
            pseudo: 'bob',
            role: $roleAdmin,
            password: 'bob123'
        );

        $userCarla = $this->createUser(
            nom: 'Durand',
            prenom: 'Carla',
            telephone: '0600000003',
            email: 'carla@example.com',
            pseudo: 'carla',
            role: $roleUser,
            password: 'carla123'
        );

        $manager->persist($userAlice);
        $manager->persist($userBob);
        $manager->persist($userCarla);

        $resource1 = (new Ressources())
            ->setTitre('Guide Symfony')
            ->setContenu('Introduction aux bases de Symfony 7.4.')
            ->setValide(true)
            ->setEstVisible(true)
            ->setVisibilite(VisibiliteStatut::PUBLIC)
            ->setUtilisateur($userAlice);

        $resource2 = (new Ressources())
            ->setTitre('Checklist API Platform')
            ->setContenu('Points clefs pour publier une API propre.')
            ->setValide(false)
            ->setEstVisible(true)
            ->setVisibilite(VisibiliteStatut::AMI)
            ->setUtilisateur($userBob);

        $resource3 = (new Ressources())
            ->setTitre('Guide securite API')
            ->setContenu('Bonnes pratiques pour securiser les endpoints.')
            ->setValide(true)
            ->setEstVisible(true)
            ->setVisibilite(VisibiliteStatut::PUBLIC)
            ->setUtilisateur($userCarla);

        $manager->persist($resource1);
        $manager->persist($resource2);
        $manager->persist($resource3);

        $category1 = (new Categories())
            ->setLibelle('Symfony')
            ->setCouleur('#1f4e79')
            ->setResource($resource1);

        $category2 = (new Categories())
            ->setLibelle('API')
            ->setCouleur('#2f7d32')
            ->setResource($resource2);

        $category3 = (new Categories())
            ->setLibelle('Securite')
            ->setCouleur('#8a6d3b')
            ->setResource($resource3);

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);

        $partage1 = (new Partages())
            ->setResource($resource1)
            ->setUtilisateur($userBob)
            ->setDatePartage(new \DateTime());

        $partage2 = (new Partages())
            ->setResource($resource2)
            ->setUtilisateur($userCarla)
            ->setDatePartage(new \DateTime());

        $manager->persist($partage1);
        $manager->persist($partage2);

        $adorer1 = (new Adorer())
            ->setResource($resource1)
            ->setUtilisateur($userCarla)
            ->setDateAdorer(new \DateTime());

        $adorer2 = (new Adorer())
            ->setResource($resource2)
            ->setUtilisateur($userAlice)
            ->setDateAdorer(new \DateTime());

        $adorer3 = (new Adorer())
            ->setResource($resource3)
            ->setUtilisateur($userBob)
            ->setDateAdorer(new \DateTime());

        $manager->persist($adorer1);
        $manager->persist($adorer2);
        $manager->persist($adorer3);

        $favori1 = (new Favoris())
            ->setResource($resource1)
            ->setUtilisateur($userBob);

        $favori2 = (new Favoris())
            ->setResource($resource3)
            ->setUtilisateur($userAlice);

        $manager->persist($favori1);
        $manager->persist($favori2);

        $manager->flush();
    }

    private function createUser(
        string $nom,
        string $prenom,
        string $telephone,
        string $email,
        string $pseudo,
        RolesUtilisateurs $role,
        string $password
    ): Utilisateurs {
        $user = (new Utilisateurs())
            ->setNom($nom)
            ->setPrenom($prenom)
            ->setTelephone($telephone)
            ->setEmail($email)
            ->setPseudo($pseudo)
            ->setRole($role)
            ->setStatusCompte(true);
        $user->setMotDePasse($this->passwordHasher->hashPassword($user, $password));

        return $user;
    }
}
