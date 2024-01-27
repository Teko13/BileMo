<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientUser;
use App\GetUsersWithHateoas;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ClientUserController extends AbstractController
{
    public function __construct(private GetUsersWithHateoas $getUsersWithHateoas, private UserPasswordHasherInterface $hasher,private EntityManagerInterface $em, private SerializerInterface $serializerInterface, private TokenStorageInterface $tokenStorage, private TagAwareCacheInterface $tagAwareCacheInterface, private ClientUserRepository $clientUserRepository) {}
    #[Route('/api/users', name: 'get_users', methods: ["GET"])]
    #[IsGranted("ROLE_CLIENT", message: "Vous n'avez pas les droits suffisant pour cet actions")]
    public function getUsers(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
        * @var Client
        */
        $client = $token->getUser();
        $idCache = "id_cache_user";
        $jsonClienUser = $this->tagAwareCacheInterface->get($idCache, function (ItemInterface $item) use ($client) {
            echo "trace";
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
    #[IsGranted("ROLE_CLIENT", message: "Vous n'avez pas les droits suffisant pour cet actions")]
    public function getUserDetail(ClientUser $clientUser): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        if($clientUser->getClient() === $client) {
            $jsonClientUser = $this->serializerInterface->serialize($this->getUsersWithHateoas->user($clientUser), "json", ["groups" => 'get_client_user']);
            return new JsonResponse(
                $jsonClientUser,
                JsonResponse::HTTP_OK,
                [],
                true
            );
        }
        return new JsonResponse(
            null,
            JsonResponse::HTTP_FORBIDDEN
        );
    }
    #[Route("/api/users", name:"add_user", methods: ["POST"])]
    #[IsGranted("ROLE_CLIENT", message: "Vous n'avez pas les droits suffisant pour cet actions")]
    public function addUser(Request $request): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        $newClientUser = $this->serializerInterface->deserialize($request->getContent(), ClientUser::class, "json");
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
    #[IsGranted("ROLE_CLIENT", message: "Vous n'avez pas les droits suffisant pour cet actions")]
    public function deleteUser(ClientUser $clientUser): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        /**
         * @var Client
         */
        $client = $token->getUser();
        if($clientUser->getClient() === $client)
        {
            $this->em->remove($clientUser);
            $this->em->flush();
            // refresh cache
            $this->tagAwareCacheInterface->invalidateTags([$client->getName()."users"]);
            return new JsonResponse(
                null,
                JsonResponse::HTTP_NO_CONTENT
            );
        }
        return new JsonResponse(
                null,
                JsonResponse::HTTP_FORBIDDEN
            );
    }
}
