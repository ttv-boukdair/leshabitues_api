<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $this->generateUsers(5,$manager);

        $manager->flush();
    }

    function generateUsers(int $number,ObjectManager $manager)
    {

         // generate  2 adminstrateur
         for ($i = 0; $i < 2; $i++) {
            $user= new User();
            $user->setEmail('admin_'.$i.'@gmail.com');
            $hashedPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setNom('admin_'.$i.'_nom');
            $user->setPrenom('admin_'.$i.'prenom');
            $manager->persist($user);
        }
        // generate clients
        for ($i = 0; $i < $number; $i++) {
            $user= new User();
            $user->setEmail('client_'.$i.'@gmail.com');
            $user->setNom('client_'.$i.'_nom');
            $user->setPrenom('client_'.$i.'prenom');
            $hashedPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_CLIENT']);
            $manager->persist($user);
        }
          // generate commercant
        for ($i = 0; $i < $number; $i++) {
            $user= new User();
            $user->setEmail('commercant_'.$i.'@gmail.com');
            $user->setNom('commercant_'.$i.'_nom');
            $user->setPrenom('commercant_'.$i.'prenom');
            $hashedPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_COMMERCANT']);
            $manager->persist($user);
        }

    }
}
