<?php
namespace App;
use App\Entity\ClientUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUsersWithHateoas extends AbstractController
{
    public function users(array $users): array
    {
        $usesrsWithHateoas = [];
        foreach($users as $user)
        {
            if($user instanceof ClientUser)
            {
                $data = [
                    "user" => $user,
                    "_links" => [
                        "self" => [
                            "method" => "GET",
                            "url" => $this->generateUrl('get_user', ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                        ],
                        "delete" => [
                            "method" => "DELETE",
                            "url" => $this->generateUrl('delete_user', ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_PATH)
                        ]
                    ]
                ];
                $usesrsWithHateoas[] = $data;
            }
        }
        return $usesrsWithHateoas;
    }
    public function user(ClientUser $user): array
    {
        $usesrsWithHateoas = [];
        $data = [
            "user" => $user,
            "_links" => [
                "self" => [
                    "method" => "GET",
                    "url" => $this->generateUrl('get_user', ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_PATH)
                ],
                "delete" => [
                    "method" => "DELETE",
                    "url" => $this->generateUrl('delete_user', ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            ]
        ];
        $usesrsWithHateoas[] = $data;
        return $usesrsWithHateoas;
    }
}