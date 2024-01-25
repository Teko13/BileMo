<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientUser;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}
    public function load(ObjectManager $manager): void
    {
        for ($client = 0; $client < 5; $client++)
        {
            $newClient = new Client;
            $newClient->setName("client".$client)
            ->setRoles(["ROLE_CLIENT"])
            ->setPassword($this->hasher->hashPassword($newClient, "client"));
            $manager->persist($newClient);
            for ($user = 0; $user < 3; $user++)
            {
                $newUsesr = new ClientUser;
                $newUsesr->setEmail("clientuser$user@gmail.com")
                ->setFirstName("first_name $user")
                ->setLastName("last_name $user")
                ->setPhone(040533020)
                ->setPassword($this->hasher->hashPassword($newUsesr, "user"))
                ->setClient($newClient);
                $manager->persist($newUsesr);
            }
        }
        $jsonFile = file_get_contents(__DIR__."/data.json");
        $dataArray = json_decode($jsonFile, true);
        foreach ($dataArray[0] as $phone)
        {
            $newPhone = new Product;
            foreach ($phone as $key => $value)
            {
                $methodeName = str_replace("_", " ", $key);
                $methodeName = 'set' . str_replace(" ", "", ucwords($methodeName));
                if($methodeName === "setReleasedIn")
                {
                    $value = new \DateTime($value);
                }
                $newPhone->$methodeName($value);
            }
            $manager->persist($newPhone);

        }

        $manager->flush();
    }
}
