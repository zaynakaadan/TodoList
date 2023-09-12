<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {  
    }
    public function load(ObjectManager $manager): void
    {
        
        // Création admin
        $admin = new User();
        $admin->setUsername("Administrateur");
        $admin->setEmail("admin@todolist.com");  
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
        $admin->setRoles(["ROLE_ADMIN"]);        
        $admin->setCreatedAt(new \DateTimeImmutable);
        $listAdmin[] = $admin;
        $manager->persist($admin);


        $anonyme = new User();
        $anonyme->setUsername("Anonyme");
        $anonyme->setEmail("anonyme@todolist.com");
        $anonyme->setPassword($this->userPasswordHasher->hashPassword($anonyme, "password"));
        $anonyme->setRoles(["ROLE_USER"]); 
        $anonyme->setCreatedAt(new \DateTimeImmutable);       
        $manager->persist($anonyme);


        $user = new User();
        $user->setUsername("Utilisateur");
        $user->setEmail("user@todolist.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $listUser[] = $user;
        $manager->persist($user);


        $task = new Task();
        $task->setTitle("Tache anonyme");
        $task->setContent("Contenu");
        $task->setAuthor($anonyme);
        $task->setCreatedAt(new \DateTimeImmutable);
        $task->setIsDone(false);
        $manager->persist($task);

        $manager->flush();
    }
}
