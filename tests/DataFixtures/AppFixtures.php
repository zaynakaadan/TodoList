<?php

namespace App\Tests\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixturesTest extends WebTestCase
{
    private MockObject | UserPasswordHasherInterface | null $userPasswordHasher;
    private MockObject | ObjectManager | null $manager;

    public function setUp(): void
    {
        $password = "password"; 
        $dateImmutable = new DateTimeImmutable('@' . strtotime('now'));

        // Create a mock object for UserPasswordHasherInterface
        $this->userPasswordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->userPasswordHasher->method('hashPassword')->willReturn($password);

        // Create a mock object for ObjectManager (Doctrine ORM)
        $this->manager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->manager->method('persist')->willReturn(1);

    }

    public function testLoad(): void
    {
        $password = "password";

         $user = new User();
         $task = new Task();
         $date = new \DateTimeImmutable;
         $user->setEmail("admin@todolist.com");
         $user->setRoles(array("0" => "ROLE_ADMIN", "1" => "ROLE_USER"));
         $user->setUsername("Administrateur");
         $user->setPassword($password);
         $user->setCreatedAt($date);
         $user->addTaskRelation($task);


         // Use PHPUnit's assertion methods from the base class
        $this->assertEquals("admin@todolist.com", $user->getEmail());
        $this->assertEquals(array("0" => "ROLE_ADMIN", "1" => "ROLE_USER"), $user->getRoles());
        $this->assertEquals("Administrateur", $user->getUsername());
        $this->assertEquals("password", $user->getPassword());
        $this->assertEquals($date, $user->getCreatedAt());
        $this->assertEquals($user->getUsername(), $user->getUserIdentifier());



        $task = new Task();
        $user = new User();
        $date = new \DateTimeImmutable;
        $task->setTitle("Title");
        $task->setContent("Content");
        $task->setUser($user);
        $task->setCreatedAt($date);

        $this->assertEquals("Title", $task->getTitle());
        $this->assertEquals("Content", $task->getContent());
        $this->assertEquals($user, $task->getUser());
        $this->assertEquals($date, $task->getCreatedAt());
    }


}