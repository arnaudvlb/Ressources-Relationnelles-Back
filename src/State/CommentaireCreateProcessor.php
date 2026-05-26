<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Adorer;
use App\Entity\Amis;
use App\Entity\Commentaires;
use App\Entity\Consultations;
use App\Entity\Favoris;
use App\Entity\Message;
use App\Entity\Utilisateurs;
use App\Repository\CommentairesRepository;
use App\Repository\RessourcesRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentaireCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
        private CommentairesRepository $commentairesRepository,
        private UtilisateursRepository $utilisateursRepository,
        private RessourcesRepository $ressourcesRepository,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Commentaires && !$data instanceof Amis && !$data instanceof Message && !$data instanceof Adorer && !$data instanceof Favoris && !$data instanceof Consultations) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof Utilisateurs) {
            throw new AccessDeniedException('Utilisateur non authentifie.');
        }

        if ($data instanceof Commentaires) {
            $payloadUserId = $data->getIdUser();
            if ($payloadUserId !== null && $payloadUserId !== $currentUser->getId()) {
                throw new AccessDeniedException('Le champ id_user doit correspondre a l utilisateur connecte.');
            }

            $parentId = $data->getCommentaireParentIdInput();
            if ($parentId !== null) {
                $parentCommentaire = $this->commentairesRepository->find($parentId);

                if ($parentCommentaire === null) {
                    throw new AccessDeniedException('Le commentaire parent est introuvable.');
                }

                $data->setCommentaireParent($parentCommentaire);
            }

            if ($data->getIdResource() !== null) {
                $resource = $this->ressourcesRepository->find($data->getIdResource());
                if ($resource === null) {
                    throw new AccessDeniedException('La ressource est introuvable.');
                }
                $data->setResource($resource);
            }

            $data->setUtilisateur($currentUser);

            if ($data->getDateCreation() === null) {
                $data->setDateCreation(new \DateTime());
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data instanceof Amis) {
            if ($data->getIdAmi() !== null) {
                $ami = $this->utilisateursRepository->find($data->getIdAmi());
                if ($ami === null) {
                    throw new AccessDeniedException('Le destinataire ami est introuvable.');
                }
                $data->setAmi($ami);
            }

            $data->setDemandeur($currentUser);

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data instanceof Message) {
            if ($data->getIdDestinataire() !== null) {
                $destinataire = $this->utilisateursRepository->find($data->getIdDestinataire());
                if ($destinataire === null) {
                    throw new AccessDeniedException('Le destinataire est introuvable.');
                }
                $data->setDestinataire($destinataire);
            }

            $data->setExpediteur($currentUser);

            if ($data->getDateEnvoie() === null) {
                $data->setDateEnvoie(new \DateTime());
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data instanceof Adorer) {
            if ($data->getIdResource() !== null) {
                $resource = $this->ressourcesRepository->find($data->getIdResource());
                if ($resource === null) {
                    throw new AccessDeniedException('La ressource est introuvable.');
                }
                $data->setResource($resource);
            }

            $data->setUtilisateur($currentUser);

            if ($data->getDateAdorer() === null) {
                $data->setDateAdorer(new \DateTime());
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data instanceof Favoris) {
            if ($data->getIdResource() !== null) {
                $resource = $this->ressourcesRepository->find($data->getIdResource());
                if ($resource === null) {
                    throw new AccessDeniedException('La ressource est introuvable.');
                }
                $data->setResource($resource);
            }

            $data->setUtilisateur($currentUser);

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data instanceof Consultations) {
            if ($data->getIdResource() !== null) {
                $resource = $this->ressourcesRepository->find($data->getIdResource());
                if ($resource === null) {
                    throw new AccessDeniedException('La ressource est introuvable.');
                }
                $data->setResource($resource);
            }

            $data->setUtilisateur($currentUser);

            if ($data->getDateConsultation() === null) {
                $data->setDateConsultation(new \DateTime());
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
