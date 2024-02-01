<?php

namespace App\Entity;

use App\Repository\ClientUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\NotBlank(message: "le champ 'first_name' est obligatoir")]
    #[Assert\Length(min: 1, max: 255, minMessage: "il faut au moins {{limit}} caractère", maxMessage: "il faut un maximum de {{limit}} caractere")]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["get_client_user"])]
    #[Assert\NotBlank(message: "le champ 'last_name' est obligatoir")]
    #[Assert\Length(min: 1, max: 255, minMessage: "il faut au moins {{limit}} caractère", maxMessage: "il faut un maximum de {{limit}} caractere")]
    private ?string $last_name = null;

    #[ORM\Column]
    #[Groups(["get_client_user"])]
    #[Assert\Type(type: "integer", message: "Fournissez un numéro valide")]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(["get_client_user"])]
    #[Assert\Email(message: "Fournissez un adress email valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le champ 'password' est obligatoir")]
    #[Assert\Length(min: 1, max: 255, minMessage: "il faut au moins {{limit}} caractère", maxMessage: "il faut un maximum de {{limit}} caractere")]
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
