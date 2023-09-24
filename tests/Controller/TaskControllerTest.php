<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $urlGenerator;
    private $userRepository;
    private $user;
    private $taskRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('admin@todolist.com');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);
    }
    public function testListAction()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testListFinishAction()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testCreateAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Titre test';
        $form['task[content]'] = 'Contenu test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testToggleTaskAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Toggle Test';
        $form['task[content]'] = 'Contenu test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $form = $crawler->selectButton('Marquer comme faite')->form();
        var_dump($form);
        $this->client->submit($form);
        $this->client->followRedirect();
    }

    public function testEditAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Edit test';
        $form['task[content]'] = 'Edit test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $this->taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        $task = $this->taskRepository->findOneByTitle('Edit test');
        $taskId = $task->getId();
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', array('id' => $taskId)));
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Modifier cette tache test';
        $form['task[content]'] = 'Contenu de test';
        $this->client->submit($form);
        $this->client->followRedirect();
    }

    public function testDeleteAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Suppression Test';
        $form['task[content]'] = 'Contenu test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        
        // Get the ID of the created task
        $taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        $task = $taskRepository->findOneBy(['title' => 'Suppression Test']);

        // Delete the task
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $task->getId()]));
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}   