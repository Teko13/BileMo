<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $rom = null;

    #[ORM\Column]
    private ?int $ram = null;

    #[ORM\Column]
    private ?float $screen_size = null;

    #[ORM\Column(length: 255)]
    private ?string $screen_resolution = null;

    #[ORM\Column(length: 255)]
    private ?string $screen_technology = null;

    #[ORM\Column(length: 255)]
    private ?string $main_camera = null;

    #[ORM\Column(length: 255)]
    private ?string $selfie_camera = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $released_in = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRom(): ?int
    {
        return $this->rom;
    }

    public function setRom(int $rom): static
    {
        $this->rom = $rom;

        return $this;
    }

    public function getRam(): ?int
    {
        return $this->ram;
    }

    public function setRam(int $ram): static
    {
        $this->ram = $ram;

        return $this;
    }

    public function getScreenSize(): ?float
    {
        return $this->screen_size;
    }

    public function setScreenSize(float $screen_size): static
    {
        $this->screen_size = $screen_size;

        return $this;
    }

    public function getScreenResolution(): ?string
    {
        return $this->screen_resolution;
    }

    public function setScreenResolution(string $screen_resolution): static
    {
        $this->screen_resolution = $screen_resolution;

        return $this;
    }

    public function getScreenTechnology(): ?string
    {
        return $this->screen_technology;
    }

    public function setScreenTechnology(string $screen_technology): static
    {
        $this->screen_technology = $screen_technology;

        return $this;
    }

    public function getMainCamera(): ?string
    {
        return $this->main_camera;
    }

    public function setMainCamera(string $main_camera): static
    {
        $this->main_camera = $main_camera;

        return $this;
    }

    public function getSelfieCamera(): ?string
    {
        return $this->selfie_camera;
    }

    public function setSelfieCamera(string $selfie_camera): static
    {
        $this->selfie_camera = $selfie_camera;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReleasedIn(): ?\DateTimeInterface
    {
        return $this->released_in;
    }

    public function setReleasedIn(\DateTimeInterface $released_in): static
    {
        $this->released_in = $released_in;

        return $this;
    }

    
}
