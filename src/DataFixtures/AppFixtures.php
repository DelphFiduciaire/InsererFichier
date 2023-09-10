<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\UserAuthenticator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher){

        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {


        $user = new User();
        $user ->setEmail("orel@admin.com");

        $password = $this->hasher->hashPassword($user,'admin');
        $user->setPassword($password);

        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}
