<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientUser;
use App\Service\GetUsersWithHateoas;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClientUserController extends AbstractController
{
    public function __construct(private GetUsersWithHateoas $getUsersWithHateoas, private UserPasswordHasherInterface $hasher,private EntityManagerInterface $em, private SerializerInterface $serializerInterface, private TokenStorageInterface $tokenStorage, private TagAwareCacheInterface $tagAwareCacheInterface, private ClientUserRepository $clientUserRepository) {}
    #[Route('/api/users', name: 'get_users', methods: ["GET"])]
    public function getUsers(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
        * @var Client
        */
        $client = $token->getUser();
        $idCache = "id_cache_user";
        $jsonClienUser = $this->tagAwareCacheInterface->get($idCache, function (ItemInterface $item) use ($client) {
            $item->tag($client->getName()."users");
            $clientUsers = $client->getClientUsers()->toArray();
            return $this->serializerInterface->serialize($this->getUsersWithHateoas->users($clientUsers), "json", ["groups" => "get_client_user"]);

        });
        return new JsonResponse(
            $jsonClienUser,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
    #[Route("/api/users/{id}", name: "get_user", methods: ["GET"])]
    public function getUserDetail(ClientUser $clientUser): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        // check if loged client is given user client
        if($clientUser->getClient() === $client) {
            $jsonClientUser = $this->serializerInterface->serialize($this->getUsersWithHateoas->user($clientUser), "json", ["groups" => 'get_client_user']);
            return new JsonResponse(
                $jsonClientUser,
                JsonResponse::HTTP_OK,
                [],
                true
            );
        }
        // if loged client is not the given user client return faled response (403)
        return new JsonResponse(
            null,
            JsonResponse::HTTP_FORBIDDEN
        );
    }
    #[Route("/api/users", name:"add_user", methods: ["POST"])]
    public function addUser(ValidatorInterface $validatorInterface, Request $request): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        $newClientUser = $this->serializerInterface->deserialize($request->getContent(), ClientUser::class, "json");
        $errors = $validatorInterface->validate($newClientUser);
        if($errors->count() > 0)
        {
            $josnErrors = $this->serializerInterface->serialize($errors, "json");
            return new JsonResponse($josnErrors, JsonResponse::HTTP_BAD_REQUEST);
        }
        $newClientUser->setPassword($this->hasher->hashPassword($newClientUser, $newClientUser->getPassword()));
        $newClientUser->setClient($client);
        $this->em->persist($newClientUser);
        $this->em->flush();
        // refresh cache
        $this->tagAwareCacheInterface->invalidateTags([$client->getName()."users"]);
        return new JsonResponse(
            $this->serializerInterface->serialize($this->getUsersWithHateoas->user($newClientUser), "json", ["groups" => "get_client_user"]),
            JsonResponse::HTTP_CREATED,
            ["location" => $this->generateUrl("get_user", ["id" => $newClientUser->getId()])],
            true
        );
    }
    #[Route("/api/users/{id}", name: "delete_user", methods: ["DELETE"])]
    public function deleteUser(ClientUser $clientUser): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
        * @var Client
        */
        $client = $token->getUser();
        // check if the loged client is the client of given user
        if($clientUser->getClient() === $client)
        {
           $this->em->remove($clientUser);
           $this->em->flush();
           // refresh cache
           $this->tagAwareCacheInterface->invalidateTags([$client->getName()."users"]);
           return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }
        // if loged client is not given user client return faled response with (403)
        return new JsonResponse(null, JsonResponse::HTTP_FORBIDDEN);
    }
    #[Route("/api/users/{id}", name: "update_user", methods: ["PUT"])]
    public function updateUser(ValidatorInterface $validatorInterface, ClientUser $clientUser, Request $request): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        if($clientUser->getClient() === $client)
        {
            $updatedClientUser = $this->serializerInterface->deserialize($request->getContent(), ClientUser::class, "json", [
            AbstractNormalizer::OBJECT_TO_POPULATE => $clientUser
            ]);
            $errors = $validatorInterface->validate($updatedClientUser);
            if ($errors->count() > 0)
            {
                $jsonErrors = $this->serializerInterface->serialize($errors, "json");
                return new JsonResponse($jsonErrors, JsonResponse::HTTP_BAD_REQUEST);
            }
            $this->em->persist($updatedClientUser);
            $this->em->flush();
            $this->tagAwareCacheInterface->invalidateTags([$client->getName()."users"]);
            return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
            );
        }
        // if loged client is not given user client return faled response with (403)
        return  new JsonResponse(
            null,
            JsonResponse::HTTP_FORBIDDEN
        );
    }
}
