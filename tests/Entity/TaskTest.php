<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{

    public function testCreateAction()
    {        
        $task = new Task();
        $date = new \DateTimeImmutable;
        $task->setTitle("Title");
        $task->setContent("Content");
        $task->setCreatedAt($date);

        $this->assertEquals("Title", $task->getTitle());
        $this->assertEquals("Content", $task->getContent());
        $this->assertEquals($date, $task->getCreatedAt());
    }
}