<?php

namespace Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RelationTest extends WebTestCase
{
    public function testCreateRelation()
    {
        $password = "password";

        // Create a mock object for UserPasswordHasherInterface
        $userPasswordHasher = $this->getMockBuilder('Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $userPasswordHasher->method('hashPassword')->willReturn($password);

        // Create a User object
        $user = new User();
        $date = new \DateTimeImmutable;
        $user->setEmail("user@hotmail.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setUsername("newuser");
        $user->setPassword($password);
        $user->setCreatedAt($date);

        // Use PHPUnit's assertion methods from the base class
        $this->assertEquals("user@hotmail.com", $user->getEmail());
        $this->assertEquals(["ROLE_USER"], $user->getRoles());
        $this->assertEquals("newuser", $user->getUsername());
        $this->assertEquals("password", $user->getPassword());
        $this->assertEquals($date, $user->getCreatedAt());
        $this->assertEquals($user->getUsername(), $user->getUserIdentifier());

        $task = new Task();
        $date = new \DateTimeImmutable;
        $task->setTitle("Title");
        $task->setContent("Content");
        $task->setCreatedAt($date);
        $task->setUser($user);

        $this->assertEquals("Title", $task->getTitle());
        $this->assertEquals("Content", $task->getContent());
        $this->assertEquals($date, $task->getCreatedAt());
        $this->assertEquals($user, $task->getUser());

    }
} 
