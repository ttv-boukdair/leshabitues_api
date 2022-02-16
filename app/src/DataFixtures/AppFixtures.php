<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Offre;
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
        $this->generateOffres($manager);
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


    function generateOffres(ObjectManager $manager)
    {


        //  find commercant
        $user= new User();
        $commercants= $manager->getRepository("App:User")->findByRole("ROLE_COMMERCANT");

        for ($i = 0; $i <  count($commercants); $i++) {
        $offre= new Offre();
        $offre->setCommercant($commercants[$i]);
        $offre->setMontant(50);
        $offre->setRemise(1.5);
        $offre->setIsPublished(true);
        $offre->setPublishedAt(new \DateTimeImmutable());
        $offre->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($offre);

        $offre= new Offre();
        $offre->setCommercant($commercants[$i]);
        $offre->setMontant(100);
        $offre->setRemise(5);
        $offre->setIsPublished(true);
        $offre->setPublishedAt(new \DateTimeImmutable());
        $offre->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($offre);

        $offre= new Offre();
        $offre->setCommercant($commercants[$i]);
        $offre->setMontant(150);
        $offre->setRemise(15);
        $offre->setIsPublished(true);
        $offre->setPublishedAt(new \DateTimeImmutable());
        $offre->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($offre);
        }
    }
}
