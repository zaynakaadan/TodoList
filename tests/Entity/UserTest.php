<?php

namespace Tests\App\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\Collection; 

class UserTest extends WebTestCase
{
    public function testCreateUser()
    {
        $password = "password"; 
        $dateImmutable = new DateTimeImmutable('@' . strtotime('now'));

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
        
    }    

    public function testEraseCredentials()
    {
        // Create an instance of the User class
        $user = new User();

        // Call the eraseCredentials method
        $user->eraseCredentials();

        // Assert that any sensitive data or state in the User class is cleared/reset
        // For example, if the User class stores a temporary password, ensure it's null after erasing credentials
        $this->assertNull($user->getPassword());
    }

    public function testGetTaskRelation()
    {
    // Create a User instance
    $user = new User();

    // Use some logic to add a Task to the User's TaskRelation 
    $task = new Task();
    $user->addTaskRelation($task);

    // Call the getTaskRelation method and assert that it returns the added Task
    $taskRelation = $user->getTaskRelation();
    $this->assertInstanceOf(Collection::class, $taskRelation);
    $this->assertCount(1, $taskRelation);
    $this->assertTrue($taskRelation->contains($task));
    }

    public function testAddTaskRelation()
    {
    // Create a User instance
    $user = new User();

    // Create a Task instance
    $task = new Task();

    // Call the addTaskRelation method
    $user->addTaskRelation($task);

    // Assert that the Task has been added to the User's TaskRelation
    $taskRelation = $user->getTaskRelation();
    $this->assertTrue($taskRelation->contains($task));
    
    }

    public function testRemoveTaskRelation()
    {
    // Create a User instance
    $user = new User();

    // Create a Task instance
    $task = new Task();

    // Add the Task to the User's TaskRelation
    $user->addTaskRelation($task);

    // Call the removeTaskRelation method
    $user->removeTaskRelation($task);

    // Assert that the Task has been removed from the User's TaskRelation
    $taskRelation = $user->getTaskRelation();
    $this->assertFalse($taskRelation->contains($task));
    }

}