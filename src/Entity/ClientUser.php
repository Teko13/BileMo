<?php

namespace App\Entity;

use App\Repository\ClientUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientUserRepository::class)]
class ClientUser implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["get_client_user"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["get_client_user"])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["get_client_user"])]
    private ?string $last_name = null;

    #[ORM\Column]
    #[Groups(["get_client_user"])]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(["get_client_user"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'clientUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
